<?php
include('connect.php');

// Function to generate and insert daily records
function generateDailyRecords($conn, $dish_id, $dish_price) {
    $start_date = new DateTime('2024-07-01');
    $end_date = new DateTime('2024-07-31');

    // Fetch the starting quantity from the previous day (assuming June 30, 2024 is the previous day)
    $prevDayQuery = "SELECT ending_quantity FROM inventory_daily_record WHERE inventory_pack_id = '$dish_id' AND date < '2024-07-01' ORDER BY date DESC LIMIT 1";
    $prevDayResult = mysqli_query($conn, $prevDayQuery);
    if ($prevDayRow = mysqli_fetch_assoc($prevDayResult)) {
        $startQuantity = $prevDayRow['ending_quantity'];
    } else {
        $startQuantity = rand(10, 25); // Default starting quantity if no previous record exists
    }

    $interval = new DateInterval('P1D'); // 1 day interval
    $period = new DatePeriod($start_date, $interval, $end_date->add($interval));

    foreach ($period as $date) {
        $currentDate = $date->format('Y-m-d');

        // Check if record already exists
        $checkRecordQuery = "SELECT * FROM inventory_daily_record WHERE date = '$currentDate' AND inventory_pack_id = '$dish_id'";
        $checkRecordResult = mysqli_query($conn, $checkRecordQuery);
        if (mysqli_num_rows($checkRecordResult) > 0) {
            // Record already exists, skip this date
            continue;
        }

        // Generate values ensuring ending quantity is within the range 10-25
        do {
            $additionalQuantity = rand(5, 10); // Adjusted lower and upper bounds
            $soldQuantity = rand(5, 10); // Adjusted lower and upper bounds
            $wastedQuantity = (rand(1, 10) <= 9) ? 0 : rand(1, 3); // 90% chance of being 0, otherwise a small non-zero number
            $endingQuantity = $startQuantity + $additionalQuantity - $soldQuantity - $wastedQuantity;
        } while ($endingQuantity < 10 || $endingQuantity > 25);

        $totalSales = $soldQuantity * $dish_price;

        $insertRecordQuery = "INSERT INTO inventory_daily_record
                              (date, inventory_pack_id, starting_quantity, additional_quantity, sold_quantity, wasted_quantity, ending_quantity, total_sales) 
                              VALUES ('$currentDate', '$dish_id', '$startQuantity', '$additionalQuantity','$soldQuantity', '$wastedQuantity', '$endingQuantity', '$totalSales')";

        if (mysqli_query($conn, $insertRecordQuery)) {
            $startQuantity = $endingQuantity; // Set the starting quantity for the next day
        } else {
            echo "Error: " . $insertRecordQuery . "<br>" . mysqli_error($conn);
        }
    }
}

// Fetch all dishes from inventory_pack
$dishesQuery = "SELECT inventory_pack_id, price FROM inventory_pack";
$dishesResult = mysqli_query($conn, $dishesQuery);

while ($dish = mysqli_fetch_assoc($dishesResult)) {
    $dish_id = $dish['inventory_pack_id'];
    $dish_price = $dish['price'];
    generateDailyRecords($conn, $dish_id, $dish_price);
}

echo "Daily records populated successfully for July 2024.";

mysqli_close($conn);
?>
