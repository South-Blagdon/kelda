<?php

/**
 * Helper function to traverse the branch path and return the parent branch and final branch by reference.
 *
 * @param array $currentBranch The current branch to traverse.
 * @param array $path The path to the desired branch.
 * @return array The parent branch and final branch, or an empty array if the branch doesn't exist.
 */
function traverseBranchPath(&$currentBranch, $path)
{
    $parentBranch = null;

    foreach ($path as $key) {
        if (!isset($currentBranch[$key])) {
            $emptyArray = [];
            $emptyResult = [&$emptyArray, &$emptyArray];
            return $emptyResult; // Branch does not exist
        }

        $parentBranch = &$currentBranch;
        $currentBranch = &$currentBranch[$key];
    }

    $result = [&$parentBranch, &$currentBranch];
    return $result;
}




/**
 * Check if a branch exists.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @return bool True if the branch exists, false otherwise.
 */
function branchExists($tree, $branchPath)
{
    $path = explode('/', $branchPath);
    $currentBranch = $tree;
    $result = traverseBranchPath($currentBranch, $path);

    return !empty($result[1]);
}

/**
 * Add a branch to the array-based tree.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @param array $value The value to assign to the branch.
 * @return void
 */
function addBranch(&$tree, $branchPath, $value = [])
{
    $path = explode('/', $branchPath);
    $currentBranch = &$tree;
    $parentBranch = &$currentBranch;

    foreach ($path as $key) {
        if (!isset($currentBranch[$key])) {
            $currentBranch[$key] = [];
        }

        $parentBranch = &$currentBranch;
        $currentBranch = &$currentBranch[$key];
    }

    $parentKey = end($path);
    $parentBranch[$parentKey] = $value;
}




/**
 * Delete a branch from the array-based tree.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @return void
 */
function deleteBranch(&$tree, $branchPath)
{
    $path = explode('/', $branchPath);
    $currentBranch = &$tree;
    $result = traverseBranchPath($currentBranch, $path);
    $parentBranch = &$result[0];
    $parentKey = end($path);

    if (!empty($parentBranch)) {
        unset($parentBranch[$parentKey]);
    }
}

/**
 * Get a branch from the array-based tree.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @return mixed|null The branch if it exists, null otherwise.
 */
function getBranch(&$tree, $branchPath)
{
    $path = explode('/', $branchPath);
    $currentBranch = &$tree;
    $result = traverseBranchPath($currentBranch, $path);
    #$parentBranch = &$result[0];
    $finalBranch = $result[1];

    return $result[1]; #$finalBranch;
}


/**
 * Update the leaves of a branch in the array-based tree.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @param array $leaves The new leaves for the branch.
 * @return void
 */
function updateBranchLeaves(&$tree, $branchPath, $leaves)
{
    $result = traverseBranchPath($tree, explode('/', $branchPath));
    $branch = &$result[1];

    if ($branch !== null && is_array($leaves)) {
        foreach ($leaves as $key => $value) {
            $branch[$key] = $value;
        }
    }
    // Optional: Uncomment the lines below to print the updated $branch and $tree
    // echo "\n\$branch\n";
    // print_r($branch);
    // echo "\n\$tree\n";
    // print_r($tree);
}

/**
 * Overwrite a branch in the array-based tree.
 *
 * @param array $tree The array-based tree.
 * @param string $branchPath The path to the branch.
 * @param array $newBranch The new branch to overwrite with.
 * @return void
 */
function overwriteBranch(&$tree, $branchPath, $newBranch)
{
    $path = explode('/', $branchPath);
    $currentBranch = &$tree;
    $result = traverseBranchPath($currentBranch, $path);
    $parentBranch = &$result[0];
    $parentKey = end($path);

    if (!empty($parentBranch)) {
        $parentBranch[$parentKey] = $newBranch;
    }
}