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
}
