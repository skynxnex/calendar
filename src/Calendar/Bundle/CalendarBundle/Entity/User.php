<?php

    namespace Calendar\Bundle\CalendarBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\UserInterface;

    /**
     * @ORM\Entity
     * @ORM\Table(name="users")
     */
    class User implements UserInterface, \Serializable
    {
        public function __construct()
        {
            $this->events = new ArrayCollection();
        }

        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\Column(type="string", length=100)
         */
        protected $name;

        /**
         * @ORM\Column(type="string", length=100)
         */
        protected $password;

        /**
         * @ORM\Column(type="string", length=100)
         */
        protected $userName;

        /**
         * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
         */
        protected $events;

        /**
         * @ORM\Column(type="string", length=32)
         */
        protected $salt;

        /**
         * @ORM\Column(name="is_active", type="boolean")
         */
        protected $isActive;

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
         * Set name
         *
         * @param string $name
         * @return User
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set password
         *
         * @param string $password
         * @return User
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * Get password
         *
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Set userName
         *
         * @param string $userName
         * @return User
         */
        public function setUserName($userName)
        {
            $this->userName = $userName;

            return $this;
        }

        /**
         * Get userName
         *
         * @return string
         */
        public function getUserName()
        {
            return $this->userName;
        }

        /**
         * Add events
         *
         * @param \Calendar\Bundle\CalendarBundle\Entity\Event $events
         * @return User
         */
        public function addEvent(Event $events)
        {
            $this->events[] = $events;

            return $this;
        }

        /**
         * Remove events
         *
         * @param \Calendar\Bundle\CalendarBundle\Entity\Event $events
         */
        public function removeEvent(Event $events)
        {
            $this->events->removeElement($events);
        }

        /**
         * Get events
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getEvents()
        {
            return $this->events;
        }

        /**
         * Returns the roles granted to the user.
         *
         * <code>
         * public function getRoles()
         * {
         *     return array('ROLE_USER');
         * }
         * </code>
         *
         * Alternatively, the roles might be stored on a ``roles`` property,
         * and populated in any number of different ways when the user object
         * is created.
         *
         * @return Role[] The user roles
         */
        public function getRoles()
        {
            return array('ROLE_USER');
        }

        /**
         * Returns the salt that was originally used to encode the password.
         *
         * This can return null if the password was not encoded using a salt.
         *
         * @return string The salt
         */
        public function getSalt()
        {
            return $this->salt;
        }

        /**
         * Removes sensitive data from the user.
         *
         * This is important if, at any given point, sensitive information like
         * the plain-text password is stored on this object.
         *
         * @return void
         */
        public function eraseCredentials()
        {
        }

        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * String representation of object
         * @link http://php.net/manual/en/serializable.serialize.php
         * @return string the string representation of the object or null
         */
        public function serialize()
        {
            return serialize($this->id);
        }

        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * Constructs the object
         * @link http://php.net/manual/en/serializable.unserialize.php
         * @param string $serialized <p>
         * The string representation of the object.
         * </p>
         * @return void
         */
        public function unserialize($serialized)
        {
            $data     = unserialize($serialized);
            $this->id = $data['id'];
        }

        /**
         * @param mixed $isActive
         */
        public function setIsActive($isActive)
        {
            $this->isActive = $isActive;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getIsActive()
        {
            return $this->isActive;
        }

    }
