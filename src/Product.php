<?php

/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/9
 * Time: 15:29
 *
 * @Entity @Table(name="products")
 */
class Product
{
    /**
     * @var int
     *
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    protected $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}