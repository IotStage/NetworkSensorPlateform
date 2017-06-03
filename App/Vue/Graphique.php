<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 24/05/2017
 * Time: 15:06
 */

namespace App\Vue;


class Graphique
{

    private $id;
    private $type;
    private $options;
    private $xAsis;
    private $yAsis;
    private $series;
    private $titre;
    private $tooltrip;
    static $CREDIT = false;

    /**
     * @return mixed
     */
    public function getTooltrip()
    {
        return $this->tooltrip;
    }

    /**
     * @param mixed $tooltrip
     */
    public function setTooltrip($tooltrip)
    {
        $this->tooltrip = $tooltrip;
    }


    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Graphique constructor.
     * @param $id
     * @param $type
     */
    public function __construct()
    {
        $this->id = "container";
        $this->type = 'line';
    }

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getXAsis()
    {
        return $this->xAsis;
    }

    /**
     * @param mixed $xAsis
     */
    public function setXAsis($xAsis)
    {
        $this->xAsis = $xAsis;
    }

    /**
     * @return mixed
     */
    public function getYAsis()
    {
        return $this->yAsis;
    }

    /**
     * @param mixed $yAsis
     */
    public function setYAsis($yAsis)
    {
        $this->yAsis = $yAsis;
    }

    /**
     * @return mixed
     */
    public function getSeries()
    {
        $ser = "[";
        foreach ($this->series as $key => $value){
            if($key == count($this->series) - 1)
                $ser.=$value;
            else
                $ser.=$value.',';
        }
        $ser.="]";
        //var_dump($ser);die;
        //return $this->series;
        return $ser;
    }

    /**
     * @param mixed $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }


    public function  getGraph(){
        $val = "chart :{\n".
                "type: ".$this->getType()."\n},".
                "title: {\n".
                    "text:".$this->getType()."\n},".
                "subtile: {\n".
                    "text:".$this->getTitre()."\n},".
                "xAxis: {\n".$this->getXAsis()."\n},".
                "yAxis: {\n".$this->getYAsis()."\n},".
                "tooltrip: {\n"."text:".$this->getTitre()."\n},".
                //"credits: ".$this->getType()."\n},".
                //"plotOptions: ".$this->getType()."\n},".
                "series: {\n".$this->getSeries()."\n},"
        ;
        /*$retourner = array(
            'chart' => array('type' => $this->getType()),
            'title' => array('text' => $this->getTitre()),
            'subtile' => array('text' => $this->getTitre()),
            'xAxis' => $this->getXAsis(),
            'yAxis' => $this->getYAsis(),
            'credits' => array('enabled' => Graphique::$CREDIT),
            'plotOptions' => $this->getOptions(),
            'tooltrip' => $this->getTooltrip(),
            'series' => $this->getSeries()
        );*/

        return ($val);

    }

    public static function getGraphWithMinimalConfiguration($capteur){
        $graphique = new Graphique();
        /*$graphique->setOptions(array(
            'line' => array(
                'dataLabels' => array(
                    'enabled'=> true
                ),
                'enableMouseTracking'=> false
            ),

            ));*/
        $graphique->setYAsis(

            'title:{\n text:' .$capteur."\n},"
        );
       /* $graphique->setTooltrip(array(
            'split'=> true,
        ));*/
        $graphique->setTitre("\"test class graphique\"");

        $graphique->setType('"line"');

        $graphique->setXAsis(
            "tickmarkPlacement:". "on\n".
            "type :"."datetime(H:i:s)");

        return $graphique;
    }


}