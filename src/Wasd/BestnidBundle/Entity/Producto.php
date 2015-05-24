<?php

namespace Wasd\BestnidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Producto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wasd\BestnidBundle\Entity\Repository\ProductoRepository")
 */
class Producto
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
     * @ORM\Column(name="titulo", type="string", length=100)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_alta", type="date")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date")
     */
    private $fechaFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="vencimiento", type="integer")
     */
    private $vencimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="rutaFoto", type="string", length=255)
     */
    private $rutaFoto;
    
    private $foto;

    /**
     * @ORM\ManyToOne(targetEntity="Wasd\BestnidBundle\Entity\Categoria")
     * @ORM\JoinColumn(name="categoria", referencedColumnName="id")
     */
     private $categoria;

     /**
      * @ORM\ManyToOne(targetEntity="Wasd\BestnidBundle\Entity\Usuario", inversedBy="productos")
      * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
      */
      private $usuario;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Producto
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Producto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return Producto
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;
    
        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Producto
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set vencimiento
     *
     * @param integer $vencimiento
     * @return Producto
     */
    public function setVencimiento($vencimiento)
    {
        $this->vencimiento = $vencimiento;
    
        return $this;
    }

    /**
     * Get vencimiento
     *
     * @return integer 
     */
    public function getVencimiento()
    {
        return $this->vencimiento;
    }


    /**
     * Set rutaFoto
     *
     * @param string $rutaFoto
     * @return Producto
     */
    public function setRutaFoto($rutaFoto)
    {
        $this->rutaFoto = $rutaFoto;

        return $this;
    }

    /**
     * Get rutaFoto
     *
     * @return string 
     */
    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }
        
    /**
     * Set foto.
     *
     * @param UploadedFile $foto
     */
    public function setFoto(UploadedFile $foto = null)
    {
        $this->foto = $foto;
    }

    /**
     * Get foto.
     *
     * @return UploadedFile
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Sube la foto de la oferta copiándola en el directorio que se indica y
     * guardando en la entidad la ruta hasta la foto
     *
     * @param string $directorioDestino Ruta completa del directorio al que se sube la foto
     */
    public function subirFoto($directorioDestino)
    {
        if (null === $this->getFoto()) {
            return;
        }
        $nombreArchivoFoto = uniqid('bestnid-').'-1.'.$this->getFoto()->guessExtension();
        $this->getFoto()->move($directorioDestino, $nombreArchivoFoto);
        $this->setRutaFoto($nombreArchivoFoto);
    }

    public function __toString(){
        return $this->getTitulo();
    }

    /**
     * Set categoria
     *
     * @param \Wasd\BestnidBundle\Entity\Categoria $categoria
     * @return Producto
     */
    public function setCategoria(\Wasd\BestnidBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Wasd\BestnidBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set usuario
     *
     * @param \Wasd\BestnidBundle\Entity\Usuario $usuario
     * @return Producto
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
}