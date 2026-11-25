@extends('layouts.app')

@section('title', 'Event Report')
@section('subtitle', $event->title . ' - ' . $event->event_date->format('F d, Y'))

@section('content')

@include('admin.users._style')

    <style>
        .panel { width: 100%; margin-bottom: 18px; }
        tr.subtotal td { background: #eef6fd; font-weight: bold; }
        tr.grand td { background: #dbeaf8; font-weight: bold; }

        /* Print: hide the menu and the top bar, show only the report */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main { margin: 0 !important; }
            .content { padding: 0 !important; }
            body { background: white !important; }
            .panel { box-shadow: none !important; border: 1px solid #bbb; }
        }
    </style>

    <div class="page-bar no-print">
        <a href="{{ route('events.show', $event) }}" class="btn btn-light">&larr; Back to Event</a>
        <button type="button" class="btn" onclick="window.print()">Print Report</button>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $event->title }}</h3>
                <small>{{ $event->event_date->format('F d, Y') }} &middot; {{ $event->venue }}</small>
            </div>
        </div>

        <h3 style="font-size: 14px; margin-bottom: 8px;">Attendance by institute and program</h3>
        <p style="font-size: 12px; color: #71869a; margin-bottom: 10px;">Each student is counted once, even in a whole-day event.</p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>INSTITUTE</th>
                        <th>PROGRAM</th>
                        <th>TOTAL</th>
                        <th>MALE</th>
                        <th>FEMALE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($byInstitute as $instituteCode => $programs)
                        @foreach ($programs as $row)
                            <tr>
                                <td>{{ $instituteCode }}</td>
                                <td>{{ $row->program_code }}</td>
                                <td>{{ $row->total }}</td>
                                <td>{{ $row->male }}</td>
                                <td>{{ $row->female }}</td>
                            </tr>
                        @endforeach

                        {{-- subtotal of the institute --}}
                        <tr class="subtotal">
                            <td colspan="2">{{ $instituteCode }} total</td>
                            <td>{{ $programs->sum('total') }}</td>
                            <td>{{ $programs->sum('male') }}</td>
                            <td>{{ $programs->sum('female') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No attendance yet for this event.</td>
                        </tr>
                    @endforelse

                    <tr class="grand">
                        <td colspan="2">Grand total</td>
                        <td>{{ $grand['total'] }}</td>
                        <td>{{ $grand['male'] }}</td>
                        <td>{{ $grand['female'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h3 style="font-size: 14px; margin-bottom: 8px;">Fine summary by program</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>PROGRAM</th>
                        <th>STUDENTS WITH FINES</th>
                        <th>TOTAL FINES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fineSummary as $programCode => $summary)
                        <tr>
                            <td>{{ $programCode }}</td>
                            <td>{{ $summary['students'] }}</td>
                            <td>&#8369;{{ number_format($summary['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No fines yet. An Officer can generate them on the Fines page.</td>
                        </tr>
                    @endforelse

                    <tr class="grand">
                        <td colspan="2">Grand total fines</td>
                        <td>&#8369;{{ number_format($fineGrandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h3 style="font-size: 14px; margin-bottom: 8px;">Students with fines</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NO.</th>
                        <th>NAME</th>
                        <th>PROGRAM</th>
                        <th>MISSED SCANS</th>
                        <th>FINE</th>
                        <th>SANCTION</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penalties as $penalty)
                        <tr>
                            <td>{{ $penalty->student->student_no }}</td>
                            <td>{{ $penalty->student->user->first_name }} {{ $penalty->student->user->last_name }}</td>
                            <td>{{ $penalty->student->program->code }}</td>
                            <td>{{ $penalty->missed_scans }}</td>
                            <td>&#8369;{{ number_format($penalty->amount, 2) }}</td>
                            <td>{{ $penalty->sanction ?: '-' }}</td>
                            <td>{{ ucfirst($penalty->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No students with fines.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection
