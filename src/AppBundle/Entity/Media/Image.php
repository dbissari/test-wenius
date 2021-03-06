<?php

namespace AppBundle\Entity\Media;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Media\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idImage;

    /**
     * @var \AppBundle\Entity\Work\Intervention
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Work\Intervention", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="intervention", referencedColumnName="id", nullable=false)
     * })
     * @Groups({"details_image"})
     */
    private $intervention;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_fichier", type="string", length=255)
     * @Groups({"list_image", "details_image"})
     */
    private $nomFichier;

    /**
     * @var string
     *
     * @ORM\Column(name="repertoire", type="string", length=255)
     * @Groups({"list_image", "details_image"})
     */
    private $repertoire;

    /**
     * Fichier de l'image
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\Image(maxSize = "2048k")
     */
    private $file;


    /**
     * Get id
     *
     * @return integer
     */
    public function getIdImage()
    {
        return $this->idImage;
    }

    /**
     * Set nomFichier
     *
     * @param string $nomFichier
     * @return Image
     */
    public function setNomFichier($nomFichier)
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    /**
     * Get nomFichier
     *
     * @return string
     */
    public function getNomFichier()
    {
        return $this->nomFichier;
    }

    /**
     * Set repertoire
     *
     * @param string $repertoire
     * @return Image
     */
    public function setRepertoire($repertoire)
    {
        $this->repertoire = $repertoire;

        return $this;
    }

    /**
     * Get repertoire
     *
     * @return string
     */
    public function getRepertoire()
    {
        return $this->repertoire;
    }

    /**
     * Set intervention
     *
     * @param \AppBundle\Entity\Work\Intervention $intervention
     * @return Image
     */
    public function setIntervention(\AppBundle\Entity\Work\Intervention $intervention)
    {
        $this->intervention = $intervention;

        return $this;
    }

    /**
     * Get intervention
     *
     * @return \AppBundle\Entity\Work\Intervention
     */
    public function getIntervention()
    {
        return $this->intervention;
    }

    /**
     * Sets file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Image
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file = null)
    {
        $this->file = $file;
        $this->nomFichier = null;

        return $this;
    }

    /**
     * Get file.
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Renvoie le chemin absolu sur le serveur jusqu'au fichier uploadé
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return $this->nomFichier === null
            ? null
            : $this->getUploadRootDir().'/'.$this->repertoire.'/'.$this->nomFichier;
    }

    /**
     * Renvoie le chemin relatif jusqu'au fichier uploadé, chemin utilisé sur les pages web
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return $this->nomFichier === null
            ? null
            : $this->getUploadDir().'/'.$this->repertoire.'/'.$this->nomFichier;
    }

    /**
     * Renvoie le chemin jusqu'au répertoire des uploads sur le serveur
     *
     * @return string
     */
    private function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * Renvoie le répertoire précis où sont uploadées les images dans le répertoire des uploads sur le serveur
     *
     * @return string
     * @Groups({"list_image", "details_image"})
     */
    private function getUploadDir()
    {
        return 'uploads/images';
    }

    /**
     * Instructions exécutées avant enregistrement des infos de l'image dans la base de données
     *
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        // Définition du répertoire du fichier
        $this->repertoire = 'Operation'.$this->intervention->getOperation()->getIdOperation();

        if (null !== $this->getFile()) {
            // Génération d'un nom de fichier unique
            $filename = md5($this->idImage);
            // Ajout de l'extension au nom de fichier généré
            $this->nomFichier = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * Instructions exécutées après enregistrement des infos de l'image dans la base de données
     *
     * @ORM\PostPersist()
     */
    public function postUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // Déplacement du fichier dans le répertoire des uploads.
        // Une erreur est déchenchée automatiquement en cas d'erreur et l'enregistrement des infos de l'images est annulé
        $this->getFile()->move($this->getUploadRootDir().'/'.$this->repertoire, $this->nomFichier);

        $this->file = null;
    }

    /**
     * Supression de l'image après suppresion de son occurence.
     *
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

}
