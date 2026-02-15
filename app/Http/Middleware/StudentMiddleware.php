<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Student;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect('/'); // Not logged in
        }
        $student = Student::find($studentId);
        if (!$student || $student->Role !== 'Student') {
            session()->forget('student_id');
            return redirect('/'); // Not a student or invalid session
        }
        return $next($request);
    }
}
