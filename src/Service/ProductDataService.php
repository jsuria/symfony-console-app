<?php

namespace App\Service;

use App\Entity\ProductData;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductDataService 
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        Connection $conn,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) 
    {
        $this->conn = $conn;
        $this->validator = $validator;
        $this->entityManager = $entityManager;        
    }

    /**
     * @param void
     * 
     * @return array
     */
    public function findAll() {
        // A placeholder function in case we need to
        // fetch the products from the DB
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->select('*')->from('ProductData');

        $data = $queryBuilder->execute()->fetchAll();

        return $data;
    }

    /**
     * @param array $payload
     * 
     * @return array
     */
    public function addProduct(array $payload): int
    {
        //$entityManager = $this->container()->getManager();

        $product = new ProductData();
        $dtm = new \DateTime("now");

        $product->setProductCode($payload['product_code'])
                ->setProductName($payload['product_name'])
                ->setProductDesc($payload['product_desc'])
                ->setProductStock($payload['product_stock'])
                ->setProductPrice($payload['product_price'])
                ->setDtmAdded($dtm);

        /**/        
        if($payload['product_discontinued']){            
            $product->setDateTimeDiscontinued($dtm);
        }
        /**/
        
        $errors = $this->validator->validate($product);

        if(count($errors) > 0){
            return false;
        }

        // Prepare this one for inserting into the db
        $this->entityManager->persist($product);

        // Finalize the query
        $this->entityManager->flush();

        return $product->getProductId();
    }
}