<?php

namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 *
 * @Hateoas\Relation(
 *     "self",
 *     href= @Hateoas\Route(
 *          "show_article",
 *          parameters = { "id": "expr(object.getId())"},
 *          absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href= @Hateoas\Route(
 *          "create_article",
 *          absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "author",
 *     embedded= @Hateoas\Embedded("expr(object.getAuthor())")
 * )
 * @Hateoas\Relation(
 *     "weather",
 *     embedded= @Hateoas\Embedded("expr(service('app.weather').getCurrent())")
 * )
 *
 *
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Serializer\Since("1.0")
     *
     * @Serializer\Expose
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     *
     * @Serializer\Since("1.0")
     *
     * @Serializer\Expose
     *
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Since("2.0")
     */
    private $shortDescription;

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
        $this->author->addArticle($this);

        return $this;
    }

    public function getSortDescription(): ?string
    {
        return $this->sortDescription;
    }

    public function setSortDescription(?string $sortDescription): self
    {
        $this->sortDescription = $sortDescription;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
