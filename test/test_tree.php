<?php
require_once __DIR__ . "/../php_funcs/tree.php";

// Example array-based tree
$tree = [
	'root' => [
		'branch1' => [
			'leaf1' => 'Value 1',
			'leaf2' => 'Value 2',
			'subbranch' => [
				'subleaf' => 'Subleaf Value'
			]
		],
		'branch2' => [
			'leaf3' => 'Value 3',
			'leaf4' => 'Value 4'
		]
	]
];


echo 'Starting example tree:';
print_r($tree);

// Usage

// Check if a branch exists
if (branchExists($tree, 'root/branch1')) {
	echo "Branch 'root/branch1' exists.";
} else {
	echo "Branch 'root/branch1' does not exist.";
}echo "\n";

// Check if a branch exists
if (branchExists($tree, 'root/no_suchBranch')) {
	echo "Branch 'root/no_suchBranch' exists.";
} else {
	echo "Branch 'root/no_suchBranch' does not exist.";
}

echo "\n";

// Add a new branch
echo "Add a new branch: 'root/branch3', ['leaf5' => 'Value 5'].";
addBranch($tree, 'root/branch3', ['leaf5' => 'Value 5']);
#print_r($tree);

// Add a new branch
echo "Add a new branch: 'root/branch3/br4', ['leaf7' => 'Value 7'].";
addBranch($tree, 'root/branch3/br4', ['leaf7' => 'Value 7']);
#print_r($tree);

// Add a new branch
echo "Add a new branch: 'root/branch3/br4/br5/br6', ['leaf8' => 'Value 8'].";
addBranch($tree, 'root/branch3/br4/br5/br6', ['leaf7' => 'Value 7']);
print_r($tree);


// Delete a branch
deleteBranch($tree, 'root/branch2');
echo "delete: 'root/branch2'";
print_r($tree);

// Get a branch
$branch = getBranch($tree, 'root/branch1');
echo "getBranch(\$tree, 'root/branch1')\n";
print_r($branch);

echo "\n";

// Update leaves of a branch
$treeRef =& $tree;
updateBranchLeaves($tree, 'root/branch1', ['leaf1' => 'New Value 1', 'leaf2' => 'New Value 2']);
echo "update : 'root/branch1', ['leaf1' => 'New Value 1', 'leaf2' => 'New Value 2']";
print_r($tree);

// Overwrite a branch
$newBranch = [
	'leaf6' => 'Value 6',
	'leaf7' => 'Value 7'
];
overwriteBranch($tree, 'root/branch1', $newBranch);
echo "overwrite branch: 'root/branch1', \$newBranch";
print_r($tree);

print_r($tree);