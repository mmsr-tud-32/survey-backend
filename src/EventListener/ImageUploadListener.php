<?php

namespace App\EventListener;

use App\Entity\SurveyImage;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author G.J.W. Oolbekkink <g.j.w.oolbekkink@gmail.com>
 * @since 19/06/2019
 */
class ImageUploadListener {
    private $uploader;

    public function __construct(FileUploader $uploader) {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity) {
        // upload only works for SurveyImage entities
        if (!$entity instanceof SurveyImage) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setImage($file->getFilename());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getObject();

        $this->uploadFile($entity);
    }
}
