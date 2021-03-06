<?php

namespace Wasd\BestnidBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoriaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoriaRepository extends EntityRepository
{
	public function findAll(){
    $em = $this->getEntityManager();
    $consulta = $em->createQuery('
      SELECT c FROM WasdBestnidBundle:Categoria c
      ORDER BY c.nombre'
      );

    return $consulta->getResult();
  }

}
