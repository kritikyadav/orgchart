<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="orgchart.css"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>
<script>
        function ClearFields(){
        document.getElementById("Input").value = "";
    }
</script>
<div id="form">
    <form method="post">
        <fieldset>
            <input type="text" id="Input" name="title" placeholder='Enter Name' value='<?php if(isset($_POST['title'])) { echo trim($_POST['title']); }?>'><br>
            <input type="submit" id="search" name="submit" class="button" value="Search" />
            <input type="submit" id="reset" class="button" onclick="ClearFields();" value="Reset"  />
            <input type="button" value="Print" onClick="window.print()">
        </fieldset> 
    </form>
</div>

<div id= "section-to-print"> 
<?php
$tree = array(
    'IPA IPA'    => null,    
    'Yogesh Patel' => 'IPA IPA',
    'Akshay Limba' => 'Yogesh Patel' ,
    'Amee Unadkat' => 'Yogesh Patel',
    'Amit Limba' => 'Yogesh Patel',
    'Akash Shah' => 'Amit Limba',
    'Vishal Pipaliya' => 'Akash Shah',
    'Kritik Yadav' => 'Yogesh Patel',
    'Marmik Parikh' => 'Yogesh Patel',
    'Mitesh Sukhadiya' => 'Yogesh Patel',
    'Paras Monapara' => 'Yogesh Patel',
    'Parth Sabhadiya' => 'Yogesh Patel',
    'Pulastya Sharma' => 'Yogesh Patel',
    'Rajan Sherathiya' => 'Yogesh Patel',
    'Foram Kasudiya' => 'Rajan Sherathiya',
    'Lokesh Mundra ' => 'Rajan Sherathiya',
    'Pooja Mehta ' => 'Rajan Sherathiya',
    'Priyanka Parab' => 'Rajan Sherathiya',
    'Shubhambhai Mandaliya' => 'Yogesh Patel',

    'Kalpesh Thakor' => 'Zulfa Chudiwala',
    'Shaily Doshi' => 'Zulfa Chudiwala',
    'Yash Contractor' => 'Zulfa Chudiwala',
    'Poorti Shrivastava' => 'Shaily Doshi',
    'Shreya Sinha' => 'Shaily Doshi',
    'Tejeshwar Manral' => 'Shaily Doshi',
    'Jashraj Parsoya' => 'Kalpesh Thakor',
    'Prajyot Gawade' => 'Kalpesh Thakor',
    'Sushant Pawar' => 'Kalpesh Thakor',
    'Zulfa Chudiwala' => 'IPA IPA',

    'Harry' => 'Gorge',
    'Fox' => 'Gorge',
    'Gorge' => 'Don',
    'Enny' => 'Don',
    'Any' => 'Enny',
    'Bunny' => 'Caven',
    'Caven' => 'Enny',
    'Don' => 'IPA IPA'
);

    if(isset($_POST['submit'])) {
        if(isset($_POST['title'])) {
            $root = trim($_POST['title']);
            function parseTree($tree, $root) {
                $return = array();
                # Traverse the tree and search for direct children of the root
                foreach($tree as $child => $parent) {
                    # Any direct child is found
                    if($parent == $root) {
                        # Remove item from tree (we don't need to traverse this again)
                        unset($tree[$child]);
                        # Append the child into result array and parse its children
                        $return[] = array(
                            'name' => $child,
                            'children' => parseTree($tree, $child)
                        );
                    }
                }
                return empty($return) ? null : $return;    
            }
            function printTree($tree) {
                if(!is_null($tree) && count($tree) > 0) {
                    echo '<ul class="collapsible">';
                    foreach($tree as $node) {
                        echo '<li class="liStyle">'.$node['name'];
                        printTree($node['children']);
                        echo '</li>';
                    }
                    echo '</ul>';
                }
            }

            
            $result = parseTree($tree, $root);
            echo "<h4>Meditab Organization Structure</h4>";
            if($root!=''){
                echo '<ul > <li> '. $root . ' </li> ';
            }
            printTree($result);
            echo '</ul>';
        }
    }
    else{
        function parseTree($tree, $root = null) {
            $return = array();
            # Traverse the tree and search for direct children of the root
            foreach($tree as $child => $parent) {
                # Any direct child is found
                if($parent == $root) {
                    # Remove item from tree (we don't need to traverse this again)
                    unset($tree[$child]);
                    # Append the child into result array and parse its children
                    $return[] = array(
                        'name' => $child,
                        'children' => parseTree($tree, $child)
                    );
                }
            }
            return empty($return) ? null : $return;    
        }
        function printTree($tree) {
            if(!is_null($tree) && count($tree) > 0) {
                echo '<ul class="collapsible">';
                foreach($tree as $node) {
                    echo '<li class="liStyle">'.$node['name'];
                    printTree($node['children']);
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        Global $num;
        $num = 1;
        $result = parseTree($tree);
        echo "<h4>Meditab Organization Structure</h4>";
        printTree($result);
    }
?>
</div> 
</body>
</html>
