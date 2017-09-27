<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as baseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Insect
 *
 * @ORM\Table(name="insect")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InsectRepository")
 */
class Insect extends BaseUser
{


    /*********************************************************************************************************************/
    /*****************************************************///Attributs/***************************************************/
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @Assert\Length(min=1,max=3,
     *     minMessage="l'âge doit comporter au moins {{ limit }} chiffres",
     *     maxMessage="l'âge  ne peut contenir plus de {{ limit }} chiffres"
     * )
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     * @Assert\Length(min=3,max=50,
     *     minMessage="Le champs famille doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le champs famille ne peut contenir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(name="famille", type="string", length=255)
     */
    private $famille;

    /**
     * @var string
     * @Assert\Length(min=3,max=50,
     *     minMessage="Le champs race doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le champs race ne peut contenir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(name="race", type="string", length=255)
     */
    private $race;

    /**
     * @var string
     * @Assert\Length(min=3,max=100,
     *     minMessage="Le champs nourriture doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le champs nourriture ne peut contenir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(name="nourriture", type="string", length=255)
     */
    private $nourriture;


    /**
     * @ORM\ManyToMany(targetEntity="Insect")
     * @ORM\JoinTable(name="amis",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ami_insect_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $amis;


    /*********************************************************************************************************************/
    /*******************************************************///méthode/***************************************************/
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Insect
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set famille
     *
     * @param string $famille
     *
     * @return Insect
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return string
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set race
     *
     * @param string $race
     *
     * @return Insect
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set nourriture
     *
     * @param string $nourriture
     *
     * @return Insect
     */
    public function setNourriture($nourriture)
    {
        $this->nourriture = $nourriture;

        return $this;
    }

    /**
     * Get nourriture
     *
     * @return string
     */
    public function getNourriture()
    {
        return $this->nourriture;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Insect
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }


    /**
     * fonction pour retourner la liste des amis dans un tableau
     * @return array
     */
    public function getAmiList()
    {
        return $this->amis->toArray();
    }

    /**
     * @param  Insect $ami
     * @return void
     */
    public function addAmi(Insect $ami)
    {
        // méthode pour insérer un ami dans mes amis ($amis)
        if (!$this->amis->contains($ami)) {
            $this->amis->add($ami);
        }
    }

    /**
     * @param  Insect $ami
     * @return void
     */
    public function removeAmi(Insect $ami)
    {
        // méthode pour retirer un ami dans mes amis ($amis)
        if ($this->amis->contains($ami)) {
            $this->amis->removeElement($ami);
        }
    }


    /*Si vous surpassez la méthode __construct () dans votre classe d'utilisateur,
     n'oubliez pas d'appeler parent :: __ construct (),
     car la classe d'utilisateur de base dépend de ceci pour initialiser certains champs.*/
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

}
