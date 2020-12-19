<?php
namespace App\Services;

class TurnamentManager {

public static function getRanking($scoresA,$scoresB, $idGroup,$idSet,$idsA, $idsB, $consolante, $consolanteId): Array
    {
        
            foreach ($idSet as $key => $value) {
                if($idSet[$key]!="0")
                {
                   //To do more than 2 sets  
                    //unset($idGroup[$key]);
                    $idGroup[$key]=2;
                }
                else
                {
                    $idGroup[$key]=1;
                }
             }



             $scoresA = array_map('intval', $scoresA);
             $scoresB = array_map('intval', $scoresB);
             $idGroup = array_map('intval', $idGroup);

             $idsA = array_map('intval', $idsA);
             $idsB = array_map('intval', $idsB);


   


             $teams = array();
             
             $idsAU = array_unique($idsA);
             $idsBU = array_unique($idsB);
             $idArray = array_merge($idsAU, $idsBU);
             $idArray= array_values(array_unique($idArray));

             $idGroup= array_values( $idGroup);


            


             foreach(range(0,sizeof($idArray)-1) as $i)
             {
                
                $stats = array();
                $stats['id']=$idArray[$i];
                $stats['wins']=0;
                $stats['quoSet']=0;
                $stats['quoPoints']=0;
                array_push($teams, $stats);
             }
             
            $i=0;
             $gi=1;
              
              
           
             while($i < count($idSet))
             {
                 $totScoreA=0;
                 $totScoreB=0; 
                 $setWonsA=0;
                 $setWonsB=0;
                 
                 $numberOfSets = $idGroup[$gi-1];
                 

                $idteamA = $idsA[$i];
                $idteamB = $idsB[$i];
                $key = array_search($idteamA, array_column($teams, 'id'));
                $key2 = array_search($idteamB, array_column($teams, 'id'));

                foreach(range(0, $numberOfSets-1) as $j)
                {
            

                    if( $scoresA[$j+$i] > $scoresB[$j+$i])
                    {
                        $setWonsA++;
                    }
                    else
                    {
                        $setWonsB++;
                    }


                    $teams[$key]['quoSet']+=$setWonsA;
                    $teams[$key]['quoPoints']+=$scoresA[$j+$i];

                    $teams[$key2]['quoSet']+=$setWonsB;
                    $teams[$key2]['quoPoints']+=$scoresB[$j+$i];
              
                }

                
               
                

                if( $setWonsA > $setWonsB)
                {

                    $idteam = $idsA[$i];
                    $key = array_search($idteam, array_column($teams, 'id'));


                    $teams[$key]['id']=$idteam;
                    $teams[$key]['wins']++;
                       
                    
                }
                else if( $setWonsA < $setWonsB)
                {

                    $idteam = $idsB[$i];
                    $key = array_search($idteam, array_column($teams, 'id'));

                    $teams[$key]['id']=$idteam;
                    $teams[$key]['wins']++;
                    
                    
                }
      
                
                	$i+=$numberOfSets;
                	$gi++;

             }

             usort($teams, function($a, $b)
            {

                if($a['wins'] > $b['wins'])
                {
                    return -1;
                }
                else if($a['wins'] == $b['wins'])
                {
                     if($a['quoSet'] > $b['quoSet'])
                    {
                        return -1;
                    }
                    else if($a['quoSet'] == $b['quoSet'])
                    {
                            if($a['quoPoints'] > $b['quoPoints'])
                            {
                                 return -1;
                            }
                            else return 1;                            
                    }
                    else return 1; 
                    
                }
                else return 1; 

                
            });

            $t = array();
            $t['teamsRanking'] = $teams;
            if($consolante)
            {
                //On sauvegarde la consolante
                $file = 'consolante'.$consolanteId.'.json';
                file_put_contents($file, json_encode($t));
            }
        return $t;
    }
}