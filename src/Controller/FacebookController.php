<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DtlSocial\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use DtlSocial\Service\Social\Facebook;
use DtlSocial\Entity\Facebook as FacebookEntity;

class FacebookController extends AbstractActionController {

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var Facebook 
     */
    protected $facebookService;

    public function indexAction() {
        $user = $this->identity();
        
        $facebook = $this->getEntityManager()->getRepository(FacebookEntity::class)
                ->findOneBy(['user' => $user]);
        
        if ($facebook) {
            $profile = $this->getFacebookService()->getProfile($facebook);
        } else {
            $profile = false;
        }
        
        return new ViewModel([
            'profile' => $profile,
        ]);
    }

    public function oauthAction() {
        $code = $this->params()->fromQuery('code');

        if (!isset($code)) {
            return $this->redirect()->toRoute('dtl-admin/dtl-social');
        }

        $signIn = $this->getFacebookService()->signIn($code);
        
        if (!$signIn) {
            return $this->redirect()->toRoute('dtl-admin/dtl-social');
        }

        return $this->redirect()->toRoute('dtl-admin/dtl-social/facebook');
    }
    
    public function disconnectAction() {
        $user = $this->identity();
        
        $facebook = $this->getEntityManager()->getRepository(FacebookEntity::class)
                ->findOneBy(['user' => $user]);
        
        if ($facebook) { 
            $this->getEntityManager()->remove($facebook);
            $this->getEntityManager()->flush();
            $this->getFacebookService()->revoke($facebook);
        }
        
        return $this->redirect()->toRoute('dtl-admin/dtl-social');
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getFacebookService(): Facebook {
        return $this->facebookService;
    }

    public function setFacebookService(Facebook $facebookService) {
        $this->facebookService = $facebookService;
        return $this;
    }

}
