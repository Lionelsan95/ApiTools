<?php


namespace App\Serializer\Listener;


use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

class ArticleListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return array(
            [
                'event'  => Events::POST_SERIALIZE,
                'format' => 'json',
                'class'  => 'App\Entity\Article',
                'method' => 'onPostSerialize',
            ]
        );
    }

    public function onPostSerialize(ObjectEvent $event){
        $object = $event->getObject();

        $date = new \DateTime();
        $event->getVisitor()->visitorProperty(new StaticPropertyMetadata('', 'deliver_at', null), $date->format('l jS \of F Y h:i:s A'));
    }
}
