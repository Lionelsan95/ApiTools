<?php

namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 *
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"detail", "list"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Serializer\Groups({"detail", "list"})
     *
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles", cascade={"all"}, fetch="EAGER")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
