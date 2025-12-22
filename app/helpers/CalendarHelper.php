<?php
/**
 * Calendar Helper
 * Generates a visual calendar for appointments
 */
class CalendarHelper
{
    private $year;
    private $month;
    private $appointments;

    public function __construct($year = null, $month = null)
    {
        $this->year = $year ?? (int)date('Y');
        $this->month = $month ?? (int)date('m');
        $this->appointments = [];
    }

    /**
     * Set appointments to display on calendar
     * @param array $appointments Array of appointment objects with appointment_date
     */
    public function setAppointments($appointments)
    {
        $this->appointments = [];
        foreach ($appointments as $apt) {
            $date = is_object($apt) ? $apt->appointment_date : $apt['appointment_date'];
            if (!isset($this->appointments[$date])) {
                $this->appointments[$date] = [];
            }
            $this->appointments[$date][] = $apt;
        }
    }

    /**
     * Get appointments for a specific date
     */
    public function getAppointmentsForDate($date)
    {
        return $this->appointments[$date] ?? [];
    }

    /**
     * Get previous month link parameters
     */
    public function getPrevMonth()
    {
        $prevMonth = $this->month - 1;
        $prevYear = $this->year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }
        return ['month' => $prevMonth, 'year' => $prevYear];
    }

    /**
     * Get next month link parameters
     */
    public function getNextMonth()
    {
        $nextMonth = $this->month + 1;
        $nextYear = $this->year;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }
        return ['month' => $nextMonth, 'year' => $nextYear];
    }

    /**
     * Get month name
     */
    public function getMonthName()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    /**
     * Get year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get month number
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Render the calendar HTML
     */
    public function render($baseUrl = '')
    {
        $firstDay = mktime(0, 0, 0, $this->month, 1, $this->year);
        $daysInMonth = (int)date('t', $firstDay);
        $startDayOfWeek = (int)date('w', $firstDay); // 0 = Sunday
        $today = date('Y-m-d');
        
        $prev = $this->getPrevMonth();
        $next = $this->getNextMonth();
        
        $html = '<div class="calendar-container">';
        
        // Header with navigation
        $html .= '<div class="d-flex justify-content-between align-items-center mb-3">';
        $html .= '<a href="' . $baseUrl . '?month=' . $prev['month'] . '&year=' . $prev['year'] . '" class="btn btn-outline-primary btn-sm">';
        $html .= '<i class="fas fa-chevron-left"></i></a>';
        $html .= '<h4 class="mb-0">' . $this->getMonthName() . ' ' . $this->year . '</h4>';
        $html .= '<a href="' . $baseUrl . '?month=' . $next['month'] . '&year=' . $next['year'] . '" class="btn btn-outline-primary btn-sm">';
        $html .= '<i class="fas fa-chevron-right"></i></a>';
        $html .= '</div>';
        
        // Calendar table
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-bordered calendar-table">';
        
        // Day headers
        $html .= '<thead class="table-light"><tr>';
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days as $day) {
            $html .= '<th class="text-center">' . $day . '</th>';
        }
        $html .= '</tr></thead>';
        
        // Calendar body
        $html .= '<tbody><tr>';
        
        // Empty cells before first day
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $html .= '<td class="calendar-day empty"></td>';
        }
        
        $currentDayOfWeek = $startDayOfWeek;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            if ($currentDayOfWeek == 7) {
                $html .= '</tr><tr>';
                $currentDayOfWeek = 0;
            }
            
            $currentDate = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
            $isToday = ($currentDate === $today);
            $appointments = $this->getAppointmentsForDate($currentDate);
            $hasAppointments = !empty($appointments);
            
            $classes = ['calendar-day'];
            if ($isToday) $classes[] = 'today';
            if ($hasAppointments) $classes[] = 'has-appointments';
            
            $html .= '<td class="' . implode(' ', $classes) . '">';
            $html .= '<div class="day-number">' . $day . '</div>';
            
            if ($hasAppointments) {
                $html .= '<div class="appointments-list">';
                foreach ($appointments as $apt) {
                    $aptObj = is_object($apt) ? $apt : (object)$apt;
                    $time = date('g:i A', strtotime($aptObj->appointment_time));
                    $status = $aptObj->status ?? 'scheduled';
                    $statusClass = $this->getStatusClass($status);
                    
                    $html .= '<div class="appointment-item ' . $statusClass . '" title="' . htmlspecialchars($time) . '">';
                    $html .= '<small class="d-block text-truncate">' . $time . '</small>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            
            $html .= '</td>';
            $currentDayOfWeek++;
        }
        
        // Empty cells after last day
        while ($currentDayOfWeek < 7 && $currentDayOfWeek > 0) {
            $html .= '<td class="calendar-day empty"></td>';
            $currentDayOfWeek++;
        }
        
        $html .= '</tr></tbody></table></div>';
        
        // Legend
        $html .= '<div class="calendar-legend mt-3">';
        $html .= '<small class="text-muted me-3"><span class="legend-dot bg-primary"></span> Scheduled</small>';
        $html .= '<small class="text-muted me-3"><span class="legend-dot bg-success"></span> Confirmed</small>';
        $html .= '<small class="text-muted me-3"><span class="legend-dot bg-info"></span> Completed</small>';
        $html .= '<small class="text-muted"><span class="legend-dot bg-danger"></span> Cancelled</small>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Get CSS class for status
     */
    private function getStatusClass($status)
    {
        $classes = [
            'scheduled' => 'bg-primary bg-opacity-25',
            'confirmed' => 'bg-success bg-opacity-25',
            'completed' => 'bg-info bg-opacity-25',
            'cancelled' => 'bg-danger bg-opacity-25',
            'no_show' => 'bg-warning bg-opacity-25'
        ];
        return $classes[$status] ?? 'bg-secondary bg-opacity-25';
    }

    /**
     * Get inline styles for calendar
     */
    public static function getStyles()
    {
        return '
        <style>
            .calendar-table { table-layout: fixed; }
            .calendar-table th { width: 14.28%; }
            .calendar-day { 
                height: 100px; 
                vertical-align: top; 
                padding: 5px !important; 
                position: relative;
            }
            .calendar-day.empty { background-color: #f8f9fa; }
            .calendar-day.today { 
                background-color: rgba(13, 110, 253, 0.1); 
                border-color: #0d6efd !important;
            }
            .calendar-day.today .day-number {
                background-color: #0d6efd;
                color: white;
                border-radius: 50%;
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .day-number { 
                font-weight: 600; 
                margin-bottom: 5px;
            }
            .appointments-list { 
                max-height: 60px; 
                overflow-y: auto;
            }
            .appointment-item { 
                border-radius: 3px; 
                padding: 2px 5px; 
                margin-bottom: 2px;
                font-size: 0.75rem;
            }
            .calendar-legend { display: flex; flex-wrap: wrap; gap: 10px; }
            .legend-dot { 
                display: inline-block; 
                width: 12px; 
                height: 12px; 
                border-radius: 50%; 
                margin-right: 5px;
                vertical-align: middle;
            }
            @media (max-width: 768px) {
                .calendar-day { height: 70px; }
                .appointments-list { display: none; }
                .calendar-day.has-appointments::after {
                    content: "";
                    position: absolute;
                    bottom: 5px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 6px;
                    height: 6px;
                    background-color: #0d6efd;
                    border-radius: 50%;
                }
            }
        </style>';
    }
}
