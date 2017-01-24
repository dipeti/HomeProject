<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2016. 12. 31.
 * Time: 12:40
 */

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\PrePersist()
     */
    public function initializeAuthorization(){
        if($this->getUsername()==='admin')
                $this->addRole('ROLE_ADMIN');

    }


}