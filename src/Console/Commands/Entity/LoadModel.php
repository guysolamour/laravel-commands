<?php

namespace Guysolamour\Command\Console\Commands\Entity;

class LoadModel extends BaseEntity
{



      /**
     * @var string
     */
    protected $name;
    /**
     * @var array
     */
    protected $fields;
    /**
     * @var null|string
     */
    protected $slug;
    /**
     * @var bool
     */
    protected $timestamps;



    /**
     * CreateModel constructor.
     * @param string $name
     * @param array $fields
     * @param null|string $slug
     * @param bool $timestamps
     */
    private function __construct(string $name , array $fields, ?string $slug = null, bool $timestamps = false)
    {

        $this->name = $name;
        $this->fields = array_chunk($fields, 3);;
        $this->slug = $slug;
        $this->timestamps = $timestamps;
    }

    /**
     * @param string $name
     * @param array $fields
     * @param null|string $slug
     * @param bool $timestamps
     * @return string
     */
    public static function generate(string $name , array $fields, ?string $slug = null, bool $timestamps = false)
    {

        return
            (new LoadModel($name,$fields,$slug,$timestamps))
            ->createModel();
    }


     /**
     * @return string
     */
    private function createModel()
    {
        try {
            $stub = file_get_contents($this->TPL_PATH . '/model/model.stub');
            $data_map = $this->parseName($this->name);

            $model_path = app_path('Models/'.$data_map['{{singularClass}}'].'.php');

            $model = strtr($stub, $data_map);

            $model = $this->loadSluggableTrait($model, $data_map);

            $this->createDirIfNotExists($model_path);

            // add model and base model
            if($this->loadModelAndBaseModel($data_map, $model_path, $model)){
                return [true, $model_path];
            }

            return [false,$model_path];


        } catch (\Exception $ex) {
            throw new \RuntimeException($ex->getMessage());
        }
    }

      /**
     * @param $data_map
     * @param $model_path
     * @param $model
     */
    private function loadModelAndBaseModel($data_map, $model_path, $model): bool
    {
        if (!file_exists(app_path('Models/BaseModel.php'))) {

            $base_model_stub = file_get_contents($this->TPL_PATH . '/model/basemodel.stub');
            $base_model = strtr($base_model_stub, $data_map);
            $base_model_path = app_path('Models/BaseModel.php');
            file_put_contents($base_model_path, $base_model);
        }



        return $this->writeFile($model_path,$model);
        //file_put_contents($model_path, $model);
    }




     /**
     * @param $model
     * @param $data_map
     * @return mixed
     */
    private function loadSluggableTrait($model, $data_map): string
    {
        if (!is_null($this->slug)) {
            // the namespace
            $sluggable_trait = '    use \Cviebrock\EloquentSluggable\Sluggable;';
            $slug_mw_bait = "{\n";
            // insert the namespace in the model
            $model = str_replace($slug_mw_bait, $slug_mw_bait . $sluggable_trait, $model);

            // sluggable stub
            $sluggable_stub = file_get_contents($this->TPL_PATH . '/model/sluggable.stub');
            // replace the slug field vars
            $sluggable = strtr($sluggable_stub, $data_map);

            // insert in the model
            $route_mw_bait = 'public $timestamps = ' . $this->getTimetsamps() . ';' . "\n\n\n";

            $model = str_replace($route_mw_bait, $route_mw_bait . $sluggable, $model);

        }
        return $model;
    }

     /**
     * @param string $name
     * @return array
     */
    protected function parseName(string $name) :array
    {

        return array_merge(parent::parseName($name),[
            '{{fillable}}' => $this->getFillables(),
            '{{timestamps}}' => $this->getTimetsamps(),
            '{{slugField}}' => $this->slug,
            ]);
    }

    /**
     * @return string
     */
    private function getTimetsamps()
    {
        return $this->timestamps ? 'false' : 'true';
    }


    /**
     * Get the different field
     * @return string
     */
    private function getFillables() :string
    {
        $fillable = '';
        foreach ($this->fields as $fields) {
            foreach ($fields as $k => $field) {
                // 0 is the index of the name's field
                if ($k === 0) {
                    $fillable .= "'$field'" . ',';

                }
            }
        }
        // add slug field to the fillable properties
        if (!is_null($this->slug)) {
            $fillable .= "'{$this->slug}'";
            $fillable .= ",'slug'";
        }

        // remove the comma at the end of the string
        $fillable = rtrim($fillable,',');

        return $fillable;
    }


}

