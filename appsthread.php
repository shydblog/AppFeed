#!/usr/bin/php
<?PHP
# description=This script will create the posts for the All Applications Thread
# arrayStarted = true
# name=Create All Application Thread Postings
# clearLog=false
# foregroundOnly=false
# noParity=false

$outputPath = "/tmp/GitHub/Squidly271.github.io";
$singleRepo = $argv[1];
echo $singleRepo."\n";

function fixPopUpDescription($PopUpDescription) {
  $PopUpDescription = str_replace("'","&#39;",$PopUpDescription);
  $PopUpDescription = str_replace('"','&quot;',$PopUpDescription);
  $PopUpDescription = str_replace("<br>","\n",$PopUpDescription);
  $PopUpDescription = str_replace("<b>","",$PopUpDescription);
  $PopUpDescription = str_replace("</b>","",$PopUpDescription);
  $PopUpDescription = str_replace("<h3>","",$PopUpDescription);
  $PopUpDescription = str_replace("</h3>","",$PopUpDescription);

  return ($PopUpDescription);
}

function mySort($a, $b) {
  $sortKey = "Name";
  $sortDir = "Down";

  if ( $sortKey != "Downloads" )
  {
    $c = strtolower($a[$sortKey]);
    $d = strtolower($b[$sortKey]);
  } else {
    $c = $a[$sortKey];
    $d = $b[$sortKey];
  }

  $return1 = ($sortDir == "Down") ? -1 : 1;
  $return2 = ($sortDir == "Down") ? 1 : -1;

  if ($c > $d) { return $return1; }
  else if ($c < $d) { return $return2; }
  else { return 0; }
}

$templates = json_decode(file_get_contents("/tmp/community.applications/tempFiles/templates.json"),true);
if ( ! $templates ) {
	exit();
}
//$moderation = json_decode(file_get_contents("/tmp/community.applications/tempFiles/moderation.json"),true);
usort($templates, "mySort");

$repos = json_decode(file_get_contents("https://github.com/Squidly271/Community-Applications-Moderators/raw/master/Repositories.json"),true);

foreach ($repos as $repo)
{
  $repoURL[$repo['name']] = $repo['url'];
  $repoName[] = $repo['name'];
}


foreach ($templates as $template)
{
  $template['RepoURL'] = $repoURL[$template['RepoName']];

  $repository['name'][$template['RepoName']][] = $template;
}
natcasesort($repoName);

foreach($repoName as $repo)
{
  foreach ($templates as $template)
  {
    if ($template['RepoName'] == $repo)
    {
      $sortedRepo[$repo][] = $template;
    }
  }
  $finalRepos[$repo]['name'] = $repo;
  if ( is_array($sortedRepo[$repo]) )
  {
    $finalRepos[$repo]['templates'] = array_reverse($sortedRepo[$repo]);
  }
}

$i = 0;
foreach($finalRepos as $repo)
{
  if  ($singleRepo) {
    if ($singleRepo != $repo['name']) {
      continue;
    }
  }
  if (is_array($repo['templates']) )
  {
    $r = "<font size='7'><b>".$repo['name']."</b></font>";
    if ( ! stripos($repo['name'],"Plugin") ) {
      $r .= "<br><font size='2'><a href='".$repoURL[$repo['name']]."'>".$repoURL[$repo['name']]."</a></font>";
    }
    $o = $r."<table>";
    $flag = false;
    foreach($repo['templates'] as $template)
    {
      if ( $flag ) {
        if ( strlen($o) + strlen($output) > INF ) {
          $o .= "</table>";
          echo "Saving $outputPath/forum_post$i\n";
          file_put_contents("$outputPath/forum_post".$i,$output.$o);
          $i = $i + 1;
          $o = "$r<br><b>Continued</b><table>";
          unset($output);
        }
      }
      $flag = true;
      if ( ! $template['Icon'] ) {
        $template['Icon'] = "https://raw.githubusercontent.com/Squidly271/community.applications/master/source/community.applications/usr/local/emhttp/plugins/community.applications/images/question.png";
      }
      $o .= "<tr><td><img src='".$template['Icon']."' width='96px' onerror='this.src=&quot;https://github.com/limetech/webgui/raw/master/plugins/dynamix.docker.manager/images/question.png&quot;'></img></td>";
      $o .= "<td><b><font size='2'>".$template['Name']."</font></b>";
      $o .= "</td>";
      if ( $template['Overview'] )
      {
        $template['Description'] = $template['Overview'];
      }
      $o .= "<td><em>".htmlspecialchars_decode(fixPopUpDescription($template['Description']),ENT_QUOTES)."</em>";
      if ( $moderation[$template['Repository']] ) {
        if ( $moderation[$template['Repository']]['Blacklist']) {
          $o .= "<br><br><font color='red'>Blacklisted within CA: ";
        } 
      
        $o .= $moderation[$template['Repository']]['ModeratorComment'] ? "<br><br><font color='red'>Moderator Comment: ".$moderation[$template['Repository']]['ModeratorComment'] : "";
      }
      $o .= "</td>";
      
      if ( $template['Support'] ) {
        $o .= "<td><font color='red'><a href='".$template['Support']."' target='_blank'>Support</a></font></td></tr>";
      } else {
        $o .= "</tr>";
      }

    }
    $o .= "</table>";
  }

  if ( strlen($output) + strlen($o) > 190000000 )
  {
    file_put_contents("$outputPath/forum_post".$i,$output);

    $output = $o;

    $i = $i + 1;
  } else {
    $output .= $o;
  }
}
$output = "<!DOCTYPE html>
<html>
<head>
<title>unRaid App List</title>
</head>
<body><center><font color='green'>DID YOU KNOW THAT YOU CAN HELP FIGHT THE SPREAD OF COVID-19 BY SIMPLY INSTALLING EITHER THE BOINC OR FOLDING AT HOME APPLICATIONS?  WE ARE ALL IN THIS TOGETHER, AND EVERY LITTLE BIT HELPS US ALL - Thank You, Andrew Zawadzki</font><br><br>The best way to install applications on unRaid is with the Community Applications plugin<br>Note that this list is a raw output.  Community Applications moderates the entries so that any incompatible / non-functional apps will not appear (and it looks a lot better also</center><br><br>".$output."</body></html>";
echo "Saving $outputPath/forum_post$i\n";
file_put_contents("$outputPath/forumpost$i.html",$output);
?>