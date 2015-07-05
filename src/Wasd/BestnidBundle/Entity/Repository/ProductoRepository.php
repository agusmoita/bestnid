<?php

namespace Wasd\BestnidBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductoRepository extends EntityRepository
{
  public function buscarVigentes($fecha){
    $em = $this->getEntityManager();
    $consulta = $em->createQuery('
      SELECT p FROM WasdBestnidBundle:Producto p
      WHERE p.fechaFin > :fecha'
      );
    $consulta->setParameter('fecha', $fecha);

    return $consulta->getResult();
  }

  public function buscarPorUsuario($usuario){
    $em = $this->getEntityManager();
    $consulta = $em->createQuery('
      SELECT p FROM WasdBestnidBundle:Producto p
      WHERE p.usuario = :usuario'
      );
    $consulta->setParameter('usuario', $usuario);

    return $consulta->getResult();
  }

  public function buscarPorCategoria($categoria){
    $em = $this->getEntityManager();
    $consulta = $em->createQuery('
      SELECT p FROM WasdBestnidBundle:Producto p
      WHERE p.categoria = :categoria'
      );
    $consulta->setParameter('categoria', $categoria);

    return $consulta->getResult();
  }

  public function cantFechas($desde, $hasta){
    $em = $this->getEntityManager();
    $consulta = $em->createQuery('
      SELECT COUNT(p.id) FROM WasdBestnidBundle:Producto p
      WHERE p.fechaAlta >= :desde
      AND p.fechaAlta <= :hasta'
      );
    $consulta->setParameter('desde', $desde);
    $consulta->setParameter('hasta', $hasta);

    return $consulta->getSingleScalarResult();
  }
}
