
<?php
// Genetic Algorithm to generate a sports schedule 

// Define the number of teams participating
$noOfTeams = 8; 
 
// Create a matrix to represent the teams 
$teams = array(); 
 
for($i=0; $i<$noOfTeams; $i++) 
{ 
    for($j=0; $j<$noOfTeams; $j++) 
    { 
        // 0 indicates that the teams have not played against each other 
        $teams[$i][$j] = 0; 
    } 
} 
 
// Create the initial population 
$populationSize = 10; 
$population = array(); 
 
for($i=0; $i<$populationSize; $i++) 
{ 
    $population[$i] = generateSchedule($teams); 
} 
 
// Set number of generations 
$generations = 100; 
 
// Run the genetic algorithm 
for($i=0; $i<$generations; $i++) 
{ 
    // Select two parents 
    $parent1 = selectParent($population); 
    $parent2 = selectParent($population); 
 
    // Crossover - create offspring 
    $offspring = crossover($parent1, $parent2); 
 
    // Mutate the offspring 
    $mutatedOffspring = mutate($offspring); 
 
    // Replace least fit individual from the population 
    $leastFit = findLeastFit($population); 
    $population[$leastFit] = $mutatedOffspring; 
} 
 
// Get the fittest individual 
$fittest = findFittest($population); 
 
// Print the schedule 
printSchedule($fittest); 
 
// Function to generate a random schedule 
function generateSchedule($teams) 
{ 
    // Create an array to store the schedule 
    $schedule = array(); 
 
    // Generate random numbers 
    while(count($schedule) < count($teams)) 
    { 
        $rand = rand(0, count($teams)-1); 
 
        // Check if the number has not been generated before 
        if(!in_array($rand, $schedule)) 
            $schedule[] = $rand; 
    } 
 
    return $schedule; 
} 
 
// Function to select a parent 
function selectParent($population) 
{ 
    // Generate a random number 
    $rand = rand(0, count($population)-1); 
 
    // Return the chromosome at the randomly generated position 
    return $population[$rand]; 
} 
 
// Function to perform crossover 
function crossover($parent1, $parent2) 
{ 
    // Select a random crossover point 
    $rand = rand(1, count($parent1)-1); 
 
    // Take the sub-array from 0 to crossover point from parent1 
    $child = array_slice($parent1, 0, $rand); 
 
    // Loop through the sub-array from crossover point till end of parent2 
    for($i=$rand; $i<count($parent2); $i++) 
    { 
        // If the number is not present in child then add it 
        if(!in_array($parent2[$i], $child)) 
            $child[] = $parent2[$i]; 
    } 
 
    // Return the child 
    return $child; 
} 
 
// Function to mutate a chromosome 
function mutate($chromosome) 
{ 
    // Select a random mutation point 
    $rand = rand(0, count($chromosome)-1); 
 
    // Get the character at the mutation point 
    $mutation = $chromosome[$rand]; 
 
    // Generate a random character 
    $randChar = getRandChar($chromosome); 
 
    // Replace the character at the mutation point with the new character 
    $chromosome[$rand] = $randChar; 
 
    // Return the mutated chromosome 
    return $chromosome; 
} 
 
// Function to get a random character 
function getRandChar($chromosome) 
{ 
    while(true) 
    { 
        $rand = rand(0, count($chromosome)-1); 
        if(!in_array($rand, $chromosome)) 
            break; 
    } 
    return $rand; 
} 
 
// Function to calculate the fitness of a chromosome 
function calculateFitness($chromosome, $teams) 
{ 
    // Get the number of teams 
    $n = count($teams); 
 
    // Initialize the fitness 
    $fitness = 0; 
 
    // Loop through the chromosome 
    for($i=0; $i<$n; $i++) 
    { 
        // Get the team number 
        $team = $chromosome[$i]; 
 
        // Check if the teams have already played against each other 
        if($teams[$i][$team] == 0) 
            $fitness++; 
    } 
 
    // Return the fitness of the chromosome 
    return $fitness; 
} 
 
// Function to find the fittest chromosome 
function findFittest($population) 
{ 
    // Initialize maximum and the fittest chromosome 
    $max = -INF; 
    $fittest = -1; 
 
    // Loop through the population 
    for($i=0; $i<count($population); $i++) 
    { 
        // Calculate the fitness 
        $fitness = calculateFitness($population[$i], $teams); 
 
        // Compare the fitness with maximum 
        if($fitness > $max) 
        { 
            // Replace the maximum with the current fitness 
            $max = $fitness; 
 
            // Store the chromosome number 
            $fittest = $i; 
        } 
    } 
 
    // Return the fittest chromosome 
    return $fittest; 
} 
 
// Function to find the least fit chromosome 
function findLeastFit($population) 
{ 
    // Initialize the minimum and the chromosome 
    $min = INF; 
    $leastFit = -1; 
 
    // Loop through the population 
    for($i=0; $i<count($population); $i++) 
    { 
        // Calculate the fitness 
        $fitness = calculateFitness($population[$i], $teams); 
 
        // Compare the fitness with minimum 
        if($fitness < $min) 
        { 
            // Replace the minimum with the current fitness 
            $min = $fitness; 
 
            // Store the chromosome number 
            $leastFit = $i; 
        } 
    } 
 
    // Return the least fit chromosome 
    return $leastFit; 
} 
 
// Function to print the schedule 
function printSchedule($chromosome) 
{ 
    echo "Schedule: \n"; 
    for($i=0; $i<count($chromosome); $i++) 
    { 
        echo "Team " . ($i+1) . " vs Team " . ($chromosome[$i]+1) . "\n"; 
    } 
} 
?>