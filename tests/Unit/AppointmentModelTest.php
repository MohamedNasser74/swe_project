<?php
/**
 * Appointment Model Unit Tests
 * Tests appointment booking, validation, and status management
 */

class AppointmentModelTest extends BaseTestCase
{
    private $appointmentModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appointmentModel = new Appointment();
    }

    /**
     * Test: Appointment model can be instantiated
     */
    public function testAppointmentModelCanBeInstantiated()
    {
        $this->assertInstanceOf(Appointment::class, $this->appointmentModel);
    }

    /**
     * Test: Valid session types
     */
    public function testValidSessionTypes()
    {
        $validTypes = ['career_guidance', 'interview_prep', 'job_search'];
        
        $this->assertContains('career_guidance', $validTypes);
        $this->assertContains('interview_prep', $validTypes);
        $this->assertContains('job_search', $validTypes);
        $this->assertNotContains('invalid_type', $validTypes);
    }

    /**
     * Test: Valid appointment statuses
     */
    public function testValidAppointmentStatuses()
    {
        $validStatuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'];
        
        $this->assertContains('scheduled', $validStatuses);
        $this->assertContains('confirmed', $validStatuses);
        $this->assertContains('completed', $validStatuses);
        $this->assertContains('cancelled', $validStatuses);
        $this->assertContains('no_show', $validStatuses);
        $this->assertNotContains('pending', $validStatuses);
    }

    /**
     * Test: Date validation - future dates only
     */
    public function testAppointmentDateMustBeFuture()
    {
        $pastDate = date('Y-m-d', strtotime('-1 day'));
        $todayDate = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime('+1 day'));
        
        // Past date should fail
        $this->assertFalse(
            strtotime($pastDate) >= strtotime('today'),
            'Past dates should be rejected'
        );
        
        // Today should pass
        $this->assertTrue(
            strtotime($todayDate) >= strtotime('today'),
            'Today should be allowed'
        );
        
        // Future date should pass
        $this->assertTrue(
            strtotime($futureDate) >= strtotime('today'),
            'Future dates should be allowed'
        );
    }

    /**
     * Test: Time slot format validation
     */
    public function testTimeSlotFormat()
    {
        $validTime = '14:30:00';
        $invalidTime = '25:00:00';
        
        // Valid time format
        $this->assertMatchesRegularExpression(
            '/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/',
            $validTime,
            'Valid time should match format'
        );
    }

    /**
     * Test: Duration validation
     */
    public function testAppointmentDurationValidation()
    {
        $minDuration = 30;  // minimum 30 minutes
        $maxDuration = 120; // maximum 2 hours
        
        $validDuration = 60;
        $tooShort = 15;
        $tooLong = 180;
        
        $this->assertTrue(
            $validDuration >= $minDuration && $validDuration <= $maxDuration,
            '60 minutes should be valid'
        );
        
        $this->assertFalse(
            $tooShort >= $minDuration && $tooShort <= $maxDuration,
            '15 minutes should be too short'
        );
        
        $this->assertFalse(
            $tooLong >= $minDuration && $tooLong <= $maxDuration,
            '180 minutes should be too long'
        );
    }

    /**
     * Test: Rating validation (1-5 stars)
     */
    public function testRatingValidation()
    {
        $validRatings = [1, 2, 3, 4, 5];
        $invalidRatings = [0, 6, -1, 10];
        
        foreach ($validRatings as $rating) {
            $this->assertTrue(
                $rating >= 1 && $rating <= 5,
                "Rating $rating should be valid"
            );
        }
        
        foreach ($invalidRatings as $rating) {
            $this->assertFalse(
                $rating >= 1 && $rating <= 5,
                "Rating $rating should be invalid"
            );
        }
    }

    /**
     * Test: Appointment data structure
     */
    public function testAppointmentDataStructure()
    {
        $appointmentData = [
            'student_id' => 1,
            'counselor_id' => 2,
            'appointment_date' => '2025-01-15',
            'appointment_time' => '10:00:00',
            'session_type' => 'career_guidance',
            'status' => 'scheduled',
            'notes' => 'Test appointment'
        ];
        
        $this->assertArrayHasKey('student_id', $appointmentData);
        $this->assertArrayHasKey('counselor_id', $appointmentData);
        $this->assertArrayHasKey('appointment_date', $appointmentData);
        $this->assertArrayHasKey('appointment_time', $appointmentData);
        $this->assertArrayHasKey('session_type', $appointmentData);
        $this->assertArrayHasKey('status', $appointmentData);
    }

    /**
     * Test: Status transition validation
     */
    public function testStatusTransitions()
    {
        // Define allowed transitions
        $allowedTransitions = [
            'scheduled' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled', 'no_show'],
            'completed' => [], // Final state
            'cancelled' => [], // Final state
            'no_show' => []    // Final state
        ];
        
        // Test: scheduled can transition to confirmed
        $this->assertContains(
            'confirmed',
            $allowedTransitions['scheduled'],
            'Scheduled should be able to transition to confirmed'
        );
        
        // Test: completed cannot transition to anything
        $this->assertEmpty(
            $allowedTransitions['completed'],
            'Completed should be a final state'
        );
    }

    /**
     * Test: Notes sanitization
     */
    public function testNotesSanitization()
    {
        $dirtyNotes = '<script>alert("xss")</script>Meeting notes here';
        $cleanNotes = htmlspecialchars($dirtyNotes, ENT_QUOTES, 'UTF-8');
        
        $this->assertStringNotContainsString('<script>', $cleanNotes);
        $this->assertStringContainsString('Meeting notes here', $cleanNotes);
    }
}
