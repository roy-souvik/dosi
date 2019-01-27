<link rel="StyleSheet" href="css/tree.css" type="text/css">
<script type="text/javascript" src="js/tree.js"></script>
<script type="text/javascript">
    var Tree = new Array;
    // nodeId | parentNodeId | nodeName | nodeUrl
    Tree[0] = "1|0| Home |admin_main.php";
	Tree[1] = "2|0| Admin User |#";
    Tree[2] = "3|2| Change Password| admin_changepwd.php";
	Tree[3] = "4|2| Change Email Id| admin_changemail.php";
	Tree[4] = "5|2| Logout|javascript:logout(); ";
	Tree[5] = "6|0| Products |#";
	Tree[6] = "7|6| Add Saree Type |admin_add_saree_type.php";
	Tree[7] = "8|6| Add Products |admin_products.php";
/*
	Tree[7] = "8|0| Latest Result|#";
    Tree[8] = "9|8| Inf T20 Result |admin_inft20_result.php";
	Tree[9] = "10|8| International Result |admin_international_result.php";
	Tree[10] = "11|0| Blog |#";
	Tree[11] = "12|11| Post |admin_post.php";
	Tree[12] = "13|11| Comment |admin_comment.php";
	Tree[13] = "14|0| Tournament |#";
	Tree[14] = "15|14| Latest News |admin_latest_news.php";
	Tree[15] = "16|14| Manage Banner Image |admin_banner.php";
	Tree[16] = "17|14| Specials |admin_special.php";
	Tree[17] = "18|14| Gallery |admin_gallery.php";
	Tree[18] = "19|14| Manage Group |admin_group.php";
	Tree[19] = "20|14| Point Table |admin_point_table.php";
	Tree[20] = "21|14| Team Squads |admin_team.php";
	Tree[21] = "22|14| Fixture |admin_fixture.php";
	Tree[22] = "23|14| Latest Videos |admin_video.php";
	Tree[23] = "24|14| Quotes |admin_quotes.php";
	Tree[24] = "25|14| Rule |admin_rule.php";
	Tree[25] = "26|14| Batsman Statistics |admin_stats_batsman.php";
	Tree[26] = "27|14| Bowler Statistics |admin_stats_bowler.php";
	Tree[27] = "28|14| Fielder Statistics |admin_stats_fielder.php";
	Tree[28] = "29|14| Merchandise |admin_merchandise.php";
	Tree[29] = "30|14| Match Results |admin_score.php";
	Tree[30] = "31|14| Media |admin_media.php";
	Tree[31] = "32|14| Manage Umpire Profile |admin_umpire_profile.php";
*/	
</script>
 
<div class="tree">
	<script type="text/javascript">
        createTree(Tree);
    </script>
</div>