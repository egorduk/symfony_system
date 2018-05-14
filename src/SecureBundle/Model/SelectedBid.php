<?php

namespace UserBundle\Model;

class SelectedBid
{
    private $sum;
    private $day;
    private $comment;
    private $dateBid;
    private $isClientDate;


    public function __construct(array $data)
    {
        $this->sum = $data['sum'];
        $this->day = $data['day'];
        $this->comment = $data['comment'];
        $this->dateBid = $data['dateBid'];
        $this->isClientDate = $data['isClientDate'];
    }

    /**
     * @return mixed
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param mixed $sum
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getDateBid()
    {
        return $this->dateBid;
    }

    /**
     * @param mixed $dateBid
     */
    public function setDateBid($dateBid)
    {
        $this->dateBid = $dateBid;
    }

    /**
     * @return mixed
     */
    public function getIsClientDate()
    {
        return $this->isClientDate;
    }

    /**
     * @param mixed $isClientDate
     */
    public function setIsClientDate($isClientDate)
    {
        $this->isClientDate = $isClientDate;
    }


}