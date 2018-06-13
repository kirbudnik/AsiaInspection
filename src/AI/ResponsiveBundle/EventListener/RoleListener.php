<?php

namespace AI\ResponsiveBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AI\ResponsiveBundle\Entity\User;
use AI\ResponsiveBundle\Service\DBConnector;

class RoleListener
{
    public function __construct(DBConnector $DBC)
    {
         $this->DBC = $DBC;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // check for students, teachers, whatever ...
        if ($entity instanceof User) {
            $email = $entity->getUsername();
            $db = $this->DBC->getDBConnection();
            $conn = $db['connection'];

            $sql = "SELECT active_status FROM salesInfo WHERE email = '" . $email . "'";

            $sth = mysqli_query($conn, $sql);

            if (mysqli_num_rows($sth) > 0) {
                $status = mysqli_fetch_assoc($sth);
                if ($status['active_status'] == '2') {
                    $entity->addRole('ROLE_ADMIN');

                }
            }
        
 
            
        }

       // ... 
    }
}