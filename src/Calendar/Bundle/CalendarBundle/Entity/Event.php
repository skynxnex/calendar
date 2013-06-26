<?php

    namespace Calendar\Bundle\CalendarBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use JMS\Serializer\Annotation\ExclusionPolicy;
    use JMS\Serializer\Annotation\SerializedName;
    use JMS\Serializer\Annotation\Expose;
    use JMS\Serializer\Annotation\Type;

    /**
     * @ORM\Entity
     * @ORM\Table(name="events")
     *
     * @ExclusionPolicy("all")
     */
    class Event
    {

        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         * @Expose
         * @Type("integer")
         */
        protected $id;

        /**
         * @ORM\Column(type="string", length=100)
         * @Expose
         * @Type("string")
         */
        protected $title;

        /**
         * @ORM\Column(type="string", length=100)
         * @Expose
         * @Type("string")
         */
        protected $start;

        /**
         * @ORM\Column(type="string", length=100)
         * @Expose
         * @Type("string")
         */
        protected $end;

        /**
         * @ORM\Column(type="string", length=100)
         * @Expose
         * @SerializedName("allDay")
         * @Type("boolean")
         */
        protected $allDay;

        /**
         * @ORM\Column(type="string", length=255)
         * @Expose
         * @Type("string")
         */
        protected $url;

        /**
         * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
         * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
         */
        protected $user;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set start
     *
     * @param string $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param string $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return string 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set allDay
     *
     * @param string $allDay
     * @return Event
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;

        return $this;
    }

    /**
     * Get allDay
     *
     * @return string 
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Event
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set user
     *
     * @param \Calendar\Bundle\CalendarBundle\Entity\User $user
     * @return Event
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Calendar\Bundle\CalendarBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
