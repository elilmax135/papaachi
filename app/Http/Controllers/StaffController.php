<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\salary;
use App\Models\staff_salary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StaffController extends Controller
{
    public function staff(){

        $staff= Staff::all();
        return view('staff.index',compact('staff'));
    }








    public function addstaff(Request $request)
{
    // Validate the incoming request data

  $staff=new Staff();
  $staff->full_name=$request->staff_name;
  $staff->nic=$request->nic;
  $staff->mobile=$request->mobile;
  $staff->save();
        // Return success response
        return redirect()->back()->with('success', 'Successfully added');

}



public function updatestaff(Request $request, $id)
{
    // Validate the incoming request data

    // Find the staff by the provided ID
    $staff = Staff::find($id);  // Corrected this line

    // If staff doesn't exist, you can return an error message
    if (!$staff) {
        return redirect()->back()->with('error', 'Staff not found');
    }

    // Update the staff record
    $staff->update([
        'full_name' => $request->input('staff_name'),
        'nic' => $request->input('nic'),
        'mobile' => $request->input('mobile'),
    ]);

    // Return a success message
    return redirect()->back()->with('success', 'Successfully updated the staff.');
}




//deletestaff

public function deletestaff($id){
    $staff=Staff::find($id);
    $staff->delete();
    return redirect()->back()->with('success','staff deleted');
}





public function save(Request $request)
    {
        $salary = new Staff_Salary();
        $salary->staff_id = $request->staff_id;
        $salary->payment = $request->payment;
        $salary->save();

        return response()->json(['message' => 'Salary saved successfully!']);
    }

    // Update Salary
    public function update(Request $request, $id)
    {
        $salary = Staff_Salary::where('staff_id', $id)->first();

        if ($salary) {
            $salary->update([
                'payment' => $request->payment
            ]);
            return response()->json(['message' => 'Salary updated successfully!']);
        } else {
            return response()->json(['message' => 'No salary record found!'], 404);
        }
    }

    // Delete Salary
    public function destroy($id)
    {
        Staff_Salary::where('staff_id', $id)->delete();
        return redirect()->back()->with('success', 'Salary deleted successfully!');
    }



    public function showsalary(){

        $salary = DB::table('salary')
        ->join('sells', 'sells.id', '=', 'salary.sells_id')
        ->join('staffs', 'staffs.id', '=', 'salary.staff_id')
        ->select(
            'salary.sells_id',  // Group by the 'sells_id' in the 'salary' table
            'staffs.full_name',
            'sells.customer_name',
            DB::raw('SUM(salary.payment) as total_payment'),
            DB::raw('SUM(salary.paid) as total_paid'),
            DB::raw('SUM(salary.due) as total_due'),
            DB::raw('MAX(salary.salary_status) as salary_status'),
            DB::raw('MAX(salary.payment_date) as latest_payment_date')
        )
        ->groupBy('salary.sells_id', 'staffs.full_name', 'sells.customer_name')
        ->get();


      return view('salary.index',compact('salary'));
    }



    public function getSalaryDetails($sell_id)
{
    $salaries = DB::table('salary')
        ->join('sells', 'salary.sells_id', '=', 'sells.id')
        ->leftJoin('staffs', 'salary.staff_id', '=', 'staffs.id')
        ->select(
            'staffs.full_name',
            'salary.id',
            'salary.payment AS salary_amount',
            'salary.paid AS salary_paid',
            'salary.due AS salary_due',
            'salary.salary_status'
        )
        ->where('salary.sells_id', $sell_id)
        ->orderBy('salary.id', 'desc')
        ->get();

    return response()->json(['salaries' => $salaries]);
}
}
