<?php
namespace Ximdex\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $_relations = [];

    /**
     * Get table name
     * @return string
     */
    public static function tableName()
    {
        return (new static)->getTable();
    }

    public function isDeletable()
    {
        return true;
    }

    public function isEditable()
    {
        return true;
    }

    /******************************** Relations ********************************/

    private function applyRelations($method)
    {
        $result = null;
        foreach ($this->_relations as $relation => $data) {
            if (strcmp($method, $relation) == 0) {
                $func = $data['type'];
                switch ($func) {
                    case 'belongsTo':

                        $result = $this->$func(
                            $data['model'],
                            $data['fk'] ?? null,
                            $data['ok'] ?? null,
                            $data['relation'] ?? $method
                        );
                        break;
                    case 'belongsToMany':
                        $result = $this->$func(
                            $data['model'],
                            $data['relation'] ?? null,
                            $data['foreignPivotKey'] ?? null,
                            $data['relatedPivotKey'] ?? $method,
                            $data['parentKey'] ?? null,
                            $data['relatedKey'] ?? null,
                            $data['relation'] ?? null
                        );
                        break;
                    case 'hasMany':
                        $result = $this->$func(
                            $data['model'],
                            $data['foreignKey'] ?? null,
                            $data['localKey'] ?? null
                        );
                        break;
                    case 'hasManyThrough':
                        $result = $this->$func(
                            $data['model'],
                            $data['model1'] ?? null,
                            $data['fk'] ?? null,
                            $data['fk1'] ?? null,
                            $data['pk'] ?? null,
                            $data['pk1'] ?? null
                        );
                        break;
                    default:
                        $result = $this->$func($data['model']);
                        break;
                }
            }
        }
        return $result;
    }

    /******************************** Override magic methods ********************************/

    public function __call($method, $parameters)
    {
        $call = $this->applyRelations($method);
        return $call ?? parent::__call($method, $parameters);
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->_relations)) {
            $call = $this->getRelationshipFromMethod($key);
        }
        return $call ?? parent::__get($key);
    }

    /******************************** Override class methods ********************************/

    /**
     * Update the model in the database. Only allows fillable attributes !!
     *
     * @param  array $attributes
     * @param  array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $attributes = array_only($attributes, $this->fillable);
        return parent::update($attributes, $options);
    }

    /**
     * @inheritDoc
     */
    public static function create(array $attributes)
    {
        $model = new static($attributes);
        return $model->query()->create($model->attributes);
    }

    /********************************  Scopes   *******************************/

    /**
     * Get fields to relations
     *
     * @param $query
     * @param string $relation
     * @param array $select
     * @param string $as
     * @return mixed
     */
    public function scopeGenericRelation($query, string $relation, array $select, ?string $as = null)
    {
        $model = $this->_relations[$relation]['model'] ?? null;
        $table = $model::tableName() ?? $relation;
        $fields = '';
        if (count($select) > 0) {
            if (array_search('id', $select) === false) {
                array_unshift($select, 'id');
            }
            $fields = ":$table." . implode(",$table.", $select);
        }
        return $query->with("$relation{$fields}");
    }

    public function scopeFindToEdit($query, $id)
    {
        return $query->find($id);
    }

    public function removeJoin(Builder $Builder, $table)
    {
        foreach ($Builder->getQuery()->joins as $key => $JoinClause) {
            if ($JoinClause->table == $table) {
                unset($Builder->getQuery()->joins[$key]);
            }
        }
    }
}
