<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductData
 *
 * @ORM\Table(name="ProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 */
class ProductData
{
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $intproductdataid;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    private $strproductname;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $strproductdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $strproductcode;

    /**
     * @var int
     *
     * @ORM\Column(name="intProductStock", type="integer", nullable=false)
     */
    private $intproductstock;

    /**
     * @var string
     *
     * @ORM\Column(name="intProductPrice", type="decimal", precision=13, scale=2, nullable=false)
     */
    private $intproductprice;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmadded;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmdiscontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    //private $stmtimestamp = 'CURRENT_TIMESTAMP';
    private $stmtimestamp = null;

    public function getProductId(): ?int
    {
        return $this->intproductdataid;
    }

    public function getProductName(): ?string
    {
        return $this->strproductname;
    }

    public function setProductName(string $strproductname): self
    {
        $this->strproductname = $strproductname;

        return $this;
    }

    public function getProductDesc(): ?string
    {
        return $this->strproductdesc;
    }

    public function setProductDesc(string $strproductdesc): self
    {
        $this->strproductdesc = $strproductdesc;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->strproductcode;
    }

    public function setProductCode(string $strproductcode): self
    {
        $this->strproductcode = $strproductcode;

        return $this;
    }

    public function getProductStock(): ?int
    {
        return $this->intproductstock;
    }

    public function setProductStock(int $intproductstock): self
    {
        $this->intproductstock = $intproductstock;

        return $this;
    }

    public function getProductPrice(): ?string
    {
        return $this->intproductprice;
    }

    public function setProductPrice(string $intproductprice): self
    {
        $this->intproductprice = $intproductprice;

        return $this;
    }

    /**/
    public function getDtmAdded(): ?\DateTimeInterface
    {
        return $this->dtmadded;
    }

    public function setDtmAdded(?\DateTimeInterface $dtmadded): self
    {
        $this->dtmadded = $dtmadded;

        return $this;
    }
    /**/

    public function getDateTimeDiscontinued(): ?\DateTimeInterface
    {
        return $this->dtmdiscontinued;
    }

    public function setDateTimeDiscontinued(?\DateTimeInterface $dtmdiscontinued): self
    {
        $this->dtmdiscontinued = $dtmdiscontinued;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->stmtimestamp;
    }

    public function setTimestamp(\DateTimeInterface $stmtimestamp): self
    {
        $this->stmtimestamp = $stmtimestamp;

        return $this;
    }
}
