<?php
/**
 * Created by PhpStorm.
 * User: alexboo
 * Date: 6/17/16
 * Time: 10:10 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 * @ORM\Table(name="song")
 */
class Song {

    const MAX_NUM = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @ORM\Column(type="integer")
     */
    private $year;
    /**
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="song")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     * @var Genre
     */
    private $genre;
    /**
     * @ORM\ManyToOne(targetEntity="Singer", inversedBy="song")
     * @ORM\JoinColumn(name="singer_id", referencedColumnName="id")
     * @var Singer
     */
    private $singer;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getSinger()
    {
        return $this->singer;
    }

    /**
     * @param mixed $singer
     */
    public function setSinger(Singer $singer)
    {
        $this->singer = $singer;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre(Genre $genre)
    {
        $this->genre = $genre;
    }
}