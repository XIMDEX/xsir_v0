<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'nodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent',
        'name',
    ];

    /**
     * @inheritDoc
     */
    protected $appends = [
        'properties',
    ];

    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => null,
        'isHidden' => false,
        'isPublishable' => false,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes = array_merge($this->attributes, [
            'type' => class_basename(static::class)
        ]);
    }

    public function getPropertiesAttribute() : array
    {
        return $this->nodeProperties;
    }

    public function getIconPropertyAttribute()
    {
        return $this->properties['icon'];
    }

    public function getIsHiddenPropertyAttribute()
    {
        return $this->properties['isHidden'];
    }
}
