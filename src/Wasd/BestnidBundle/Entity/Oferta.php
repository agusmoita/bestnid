<?php

namespace Wasd\BestnidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oferta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wasd\BestnidBundle\Entity\Repository\OfertaRepository")
 */
class Oferta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal")
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="necesidad", type="string", length=255)
     */
    private $necesidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Wasd\BestnidBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */
     private $usuario;

     /**
      * @ORM\ManyToOne(targetEntity="Wasd\BestnidBundle\Entity\Producto")
      * @ORM\JoinColumn(name="producto", referencedColumnName="id")
      */
      private $producto;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set monto
     *
     * @param string $monto
     * @return Oferta
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set necesidad
     *
     * @param string $necesidad
     * @return Oferta
     */
    public function setNecesidad($necesidad)
    {
        $this->necesidad = $necesidad;

        return $this;
    }

    /**
     * Get necesidad
     *
     * @return string 
     */
    public function getNecesidad()
    {
        return $this->necesidad;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Oferta
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param \Wasd\BestnidBundle\Entity\Usuario $usuario
     * @return Oferta
     */
    public function setUsuario(\Wasd\BestnidBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Wasd\BestnidBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set producto
     *
     * @param \Wasd\BestnidBundle\Entity\Producto $producto
     * @return Oferta
     */
    public function setProducto(\Wasd\BestnidBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \Wasd\BestnidBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
