<?php
/**
 * Created by PhpStorm.
 * User: dipet
 * Date: 2017. 01. 24.
 * Time: 22:13
 */

namespace AppBundle\Security;


use AppBundle\Entity\Topic;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TopicVoter extends Voter
{
    const EDIT = 'edit';
    /**
     * @var AccessDecisionManager $accessDecisionManager
     */
    private $accessDecisionManager;
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {

        if(!in_array($attribute,[self::EDIT])){
            return false;
        }
        if(!$subject instanceof Topic){
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        if($this->accessDecisionManager->decide($token,['ROLE_ADMIN'])) return true;
        $user = $token->getUser();
        if(!$user instanceof User) return false;
        /**
         * @var Topic $topic
         */
        $topic = $subject;
        switch($attribute){
            case self::EDIT:
                return $this->canEdit($topic, $user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Topic $topic,User $user)
    {
        return $user === $topic->getHost();
    }
}