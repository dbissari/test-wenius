<?php

namespace AppBundle\Entity\Work;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Problème
 *
 * @ORM\Table(name="probleme")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Work\ProblemeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Probleme
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idProbleme;

    /**
     * @var \AppBundle\Entity\Security\Technicien
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Security\Technicien")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotNull()
     */
    private $createur;

    /**
     * @var \AppBundle\Entity\Work\Vehicule
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Work\Vehicule")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicule_concerne", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotNull()
     */
    private $vehiculeConcerne;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 50
     * )
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_detection", type="date")
     * @Assert\NotBlank()
     */
    private $dateDetection;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * Get idProbleme
     *
     * @return integer 
     */
    public function getIdProbleme()
    {
        return $this->idProbleme;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Probleme
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Probleme
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateDetection
     *
     * @param \DateTime $dateDetection
     * @return Probleme
     */
    public function setDateDetection($dateDetection)
    {
        $this->dateDetection = $dateDetection;

        return $this;
    }

    /**
     * Get dateDetection
     *
     * @return \DateTime 
     */
    public function getDateDetection()
    {
        return $this->dateDetection;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Probleme
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createur
     *
     * @param \AppBundle\Entity\Security\Technicien $createur
     * @return Probleme
     */
    public function setCreateur(\AppBundle\Entity\Security\Technicien $createur)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return \AppBundle\Entity\Security\Technicien 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set vehiculeConcerne
     *
     * @param \AppBundle\Entity\Work\Vehicule $vehiculeConcerne
     * @return Probleme
     */
    public function setVehiculeConcerne(\AppBundle\Entity\Work\Vehicule $vehiculeConcerne)
    {
        $this->vehiculeConcerne = $vehiculeConcerne;

        return $this;
    }

    /**
     * Get vehiculeConcerne
     *
     * @return \AppBundle\Entity\Work\Vehicule 
     */
    public function getVehiculeConcerne()
    {
        return $this->vehiculeConcerne;
    }

    /**
     * Instructions exécutées juste avant enregistrement du nouveau problème.
     *
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Vérifie la validité du véhicule affecté.
     *
     * @return bool
     * @Assert\IsTrue(message = "Ce véhicule n'existe pas")
     */
    public function isVehiculeValid()
    {
        return $this->vehiculeConcerne ? ($this->vehiculeConcerne->getDeletedAt() ? false : true) : false;
    }

    /**
     * Vérifie la validité de l'utilisateur affecté.
     *
     * @return bool
     * @Assert\IsTrue(message = "Ce compte n'existe pas")
     */
    public function isCreateurValid()
    {
        return $this->createur ? ($this->createur->getDeletedAt() ? false : true) : false;
    }
}
