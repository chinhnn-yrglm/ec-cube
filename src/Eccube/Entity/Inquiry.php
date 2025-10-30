<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(Inquiry::class)) {
    /**
     * Inquiry
     *
     * @ORM\Table(name="dtb_inquiry")
     *
     * @ORM\InheritanceType("SINGLE_TABLE")
     *
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     *
     * @ORM\HasLifecycleCallbacks()
     *
     * @ORM\Entity(repositoryClass="Eccube\Repository\DeliveryRepository")
     */
    class Inquiry extends AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         *
         * @ORM\Id
         *
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="name01", type="string", length=255)
         */
        private $name01;

        /**
         * @var string
         *
         * @ORM\Column(name="name02", type="string", length=255)
         */
        private $name02;

        /**
         * @var string|null
         *
         * @ORM\Column(name="kana01", type="string", length=255, nullable=true)
         */
        private $kana01;

        /**
         * @var string|null
         *
         * @ORM\Column(name="kana02", type="string", length=255, nullable=true)
         */
        private $kana02;

        /**
         * @var string
         *
         * @ORM\Column(name="email", type="string", length=255)
         */
        private $email;

        /**
         * @var string|null
         *
         * @ORM\Column(name="phone_number", type="string", length=14, nullable=true)
         */
        private $phone_number;

        /**
         * @var string|null
         *
         * @ORM\Column(name="content", type="string", length=4000, nullable=true)
         */
        private $content;

        /**
         * Get the value of id
         *
         * @return  int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Get the value of name01
         *
         * @return  string
         */
        public function getName01()
        {
            return $this->name01;
        }

        /**
         * Set the value of name01
         *
         * @param  string  $name01
         *
         * @return  self
         */
        public function setName01(string $name01)
        {
            $this->name01 = $name01;

            return $this;
        }

        /**
         * Get the value of name02
         *
         * @return  string
         */
        public function getName02()
        {
            return $this->name02;
        }

        /**
         * Set the value of name02
         *
         * @param  string  $name02
         *
         * @return  self
         */
        public function setName02(string $name02)
        {
            $this->name02 = $name02;

            return $this;
        }

        /**
         * Get the value of kana01
         *
         * @return  string|null
         */
        public function getKana01()
        {
            return $this->kana01;
        }

        /**
         * Set the value of kana01
         *
         * @param  string|null  $kana01
         *
         * @return  self
         */
        public function setKana01($kana01)
        {
            $this->kana01 = $kana01;

            return $this;
        }

        /**
         * Get the value of kana02
         *
         * @return  string|null
         */
        public function getKana02()
        {
            return $this->kana02;
        }

        /**
         * Set the value of kana02
         *
         * @param  string|null  $kana02
         *
         * @return  self
         */
        public function setKana02($kana02)
        {
            $this->kana02 = $kana02;

            return $this;
        }

        /**
         * Get the value of email
         *
         * @return  string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set the value of email
         *
         * @param  string  $email
         *
         * @return  self
         */
        public function setEmail(string $email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get the value of phone_number
         *
         * @return  string|null
         */
        public function getPhoneNumber()
        {
            return $this->phone_number;
        }

        /**
         * Set the value of phone_number
         *
         * @param  string|null  $phone_number
         *
         * @return  self
         */
        public function setPhoneNumber($phone_number)
        {
            $this->phone_number = $phone_number;

            return $this;
        }

        /**
         * Get the value of content
         *
         * @return  string|null
         */
        public function getContent()
        {
            return $this->content;
        }

        /**
         * Set the value of content
         *
         * @param  string|null  $content
         *
         * @return  self
         */
        public function setContent($content)
        {
            $this->content = $content;

            return $this;
        }

        /**
         * String representation of object
         *
         * @see http://php.net/manual/en/serializable.serialize.php
         *
         * @return string the string representation of the object or null
         *
         * @since 5.1.0
         */
        public function serialize()
        {
            return serialize([
                $this->id,
                $this->name01,
                $this->name02,
                $this->kana01,
                $this->kana02,
                $this->email,
                $this->phone_number,
                $this->content,
            ]);
        }

        /**
         * Constructs the object
         *
         * @see http://php.net/manual/en/serializable.unserialize.php
         *
         * @param string $serialized <p>
         * The string representation of the object.
         * </p>
         *
         * @return void
         *
         * @since 5.1.0
         */
        public function unserialize($serialized)
        {
            list(
                $this->id,
                $this->name01,
                $this->name02,
                $this->kana01,
                $this->kana02,
                $this->email,
                $this->phone_number,
                $this->content,
            ) = unserialize($serialized);
        }
    }
}
