// SCORE MANAGEMENT SYSTEM ENHANCEMENTS
// Main application structure using modern JavaScript

// Configuration
input.addEventListener('input', (e) => {
    ScoreController.updateScore(
      student.id,
      component,
      parseFloat(e.target.value) || null
    );
  });
  

const config = {
    apiEndpoint: '/api/scores',
    academicYear: '2024-2025',
    semester: 'Học kỳ 2',
    gradeComponents: {
      diemChuyenCan: { weight: 0.1, name: 'Điểm Chuyên cần' },
      diemKT1: { weight: 0.2, name: 'Điểm KT1' },
      diemKT2: { weight: 0.2, name: 'Điểm KT2' },
      diemThaoLuan: { weight: 0.15, name: 'Điểm Thảo luận' },
      diemCuoiKy: { weight: 0.35, name: 'Điểm Cuối kỳ' }
    }
  };
  
  // Data model classes
  class Student {
    constructor(id, code, lastName, firstName, dob, status) {
      this.id = id;
      this.studentCode = code;
      this.lastName = lastName;
      this.firstName = firstName;
      this.dob = dob;
      this.status = status;
      this.scores = {};
    }
  
    getFullName() {
      return `${this.lastName} ${this.firstName}`;
    }
  
    calculateFinalGrade() {
      let finalGrade = 0;
      let totalWeight = 0;
      
      for (const [component, score] of Object.entries(this.scores)) {
        if (score !== null && config.gradeComponents[component]) {
          finalGrade += score * config.gradeComponents[component].weight;
          totalWeight += config.gradeComponents[component].weight;
        }
      }
      
      return totalWeight > 0 ? (finalGrade / totalWeight).toFixed(2) : null;
    }
  }
  
  class Course {
    constructor(id, code, name, instructor) {
      this.id = id;
      this.code = code;
      this.name = name;
      this.instructor = instructor;
      this.students = [];
    }
    
    addStudent(student) {
      this.students.push(student);
    }
    
    getStudentByCode(code) {
      return this.students.find(student => student.studentCode === code);
    }
    
    calculateClassAverage() {
      const grades = this.students
        .map(student => parseFloat(student.calculateFinalGrade()))
        .filter(grade => !isNaN(grade));
        
      if (grades.length === 0) return null;
      
      const sum = grades.reduce((total, grade) => total + grade, 0);
      return (sum / grades.length).toFixed(2);
    }
  }
  
  // UI Components
  const ScoreManagementUI = {
    renderScoreTable(course) {
      const tableBody = document.getElementById('scoreTableBody');
      tableBody.innerHTML = '';
      
      course.students.forEach((student, index) => {
        const row = document.createElement('tr');
        
        // Add student information columns
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${student.studentCode}</td>
          <td>${student.lastName}</td>
          <td>${student.firstName}</td>
          <td>${student.dob}</td>
          <td>${student.status}</td>
        `;
        
        // Add score input fields
        Object.keys(config.gradeComponents).forEach(component => {
          const cell = document.createElement('td');
          const input = document.createElement('input');
          input.type = 'text';
          input.className = 'score-input';
          input.value = student.scores[component] || '';
          input.dataset.studentId = student.id;
          input.dataset.component = component;
          
          input.addEventListener('change', (e) => {
            ScoreController.updateScore(
              student.id,
              component,
              parseFloat(e.target.value) || null
            );
          });
          
          cell.appendChild(input);
          row.appendChild(cell);
        });
        
        // Add final grade column
        const finalGradeCell = document.createElement('td');
        finalGradeCell.className = 'final-grade';
        finalGradeCell.id = `final-grade-${student.id}`;
        finalGradeCell.textContent = student.calculateFinalGrade() || '-';
        row.appendChild(finalGradeCell);
        
        tableBody.appendChild(row);
      });
      
      this.updateStatistics(course);
    },
    
    updateStatistics(course) {
      const statsContainer = document.getElementById('classStatistics');
      const classAverage = course.calculateClassAverage();
      
      statsContainer.innerHTML = `
        <div class="stats-card">
          <h3>Thống kê lớp học</h3>
          <p>Điểm trung bình: <strong>${classAverage || '-'}</strong></p>
          <p>Tổng số sinh viên: <strong>${course.students.length}</strong></p>
          <button id="generateReportBtn" class="btn btn-primary">Xuất báo cáo chi tiết</button>
        </div>
      `;
      
      document.getElementById('generateReportBtn').addEventListener('click', () => {
        ReportGenerator.generateClassReport(course);
      });
    },
    
    renderGradeDistribution(course) {
      const grades = course.students
        .map(student => parseFloat(student.calculateFinalGrade()))
        .filter(grade => !isNaN(grade));
        
      if (grades.length === 0) return;
      
      // Group grades into ranges
      const distribution = {
        'A (8.5-10)': 0,
        'B (7.0-8.4)': 0,
        'C (5.5-6.9)': 0,
        'D (4.0-5.4)': 0,
        'F (0-3.9)': 0
      };
      
      grades.forEach(grade => {
        if (grade >= 8.5) distribution['A (8.5-10)']++;
        else if (grade >= 7.0) distribution['B (7.0-8.4)']++;
        else if (grade >= 5.5) distribution['C (5.5-6.9)']++;
        else if (grade >= 4.0) distribution['D (4.0-5.4)']++;
        else distribution['F (0-3.9)']++;
      });
      
      // Render the chart (this would use a charting library in a real implementation)
      const chartContainer = document.getElementById('gradeDistributionChart');
      chartContainer.innerHTML = '<h3>Phân phối điểm</h3>';
      
      Object.entries(distribution).forEach(([range, count]) => {
        const percentage = (count / grades.length * 100).toFixed(1);
        chartContainer.innerHTML += `
          <div class="distribution-bar">
            <div class="range-label">${range}</div>
            <div class="bar-container">
              <div class="bar" style="width: ${percentage}%"></div>
            </div>
            <div class="count-label">${count} (${percentage}%)</div>
          </div>
        `;
      });
    }
  };
  
  // Controllers
  const ScoreController = {
    currentCourse: null,
    
    loadCourse(courseId) {
      // In a real app, this would fetch from an API
      // Mock implementation for demonstration
      this.currentCourse = this.createMockCourse();
      ScoreManagementUI.renderScoreTable(this.currentCourse);
      ScoreManagementUI.renderGradeDistribution(this.currentCourse);
    },
    
    createMockCourse() {
      const course = new Course(
        1, 
        '242_eCIT4011_02', 
        'Triển khai Hệ thống thông tin quản lý', 
        'Lê Việt Hà'
      );
      
      // Add students from the image
      const students = [
        new Student(1, '23D190001', 'ĐỖ THỊ', 'TẤM ANH', '30/07/2005', 'Còn học'),
        new Student(2, '23D190002', 'NGUYỄN', 'MAI ANH', '08/08/2005', 'Còn học'),
        new Student(3, '23D190003', 'TRẦN', 'LAN ANH', '18/01/2005', 'Còn học'),
        new Student(4, '23D190004', 'NGUYỄN MAI', 'CHI', '18/03/2005', 'Còn học'),
        new Student(5, '23D190005', 'PHÙNG THỊ', 'MINH CHI', '15/05/2005', 'Còn học'),
        new Student(6, '23D190006', 'VŨ THỊ HỒNG', 'CHIẾN', '22/04/2005', 'Còn học')
      ];
      
      students.forEach(student => course.addStudent(student));
      
      // Add some sample scores
      students[0].scores = {
        diemChuyenCan: 8.5,
        diemKT1: 7.5,
        diemKT2: 8.0,
        diemThaoLuan: 9.0
      };
      
      students[1].scores = {
        diemChuyenCan: 9.0,
        diemKT1: 8.5,
        diemThaoLuan: 8.0
      };
      
      return course;
    },
    
    updateScore(studentId, component, value) {
      const student = this.currentCourse.students.find(s => s.id === studentId);
      if (student) {
        student.scores[component] = value;
        
        // Update the final grade displayed in the UI
        const finalGradeElement = document.getElementById(`final-grade-${studentId}`);
        if (finalGradeElement) {
          finalGradeElement.textContent = student.calculateFinalGrade() || '-';
        }
        
        // Update statistics and distribution
        ScoreManagementUI.updateStatistics(this.currentCourse);
        ScoreManagementUI.renderGradeDistribution(this.currentCourse);
      }
    },
    
    saveAllScores() {
      // In a real app, this would send data to an API
      console.log('Saving scores for course:', this.currentCourse);
      
      // Show confirmation message
      alert('Điểm đã được lưu thành công!');
    },
    
    importFromExcel(file) {
      // Mock implementation - in a real app this would parse an Excel file
      console.log('Importing scores from file:', file.name);
      
      // Process would happen here
      
      // Refresh the display
      ScoreManagementUI.renderScoreTable(this.currentCourse);
    }
  };
  
  // Report Generator
  const ReportGenerator = {
    generateClassReport(course) {
      // In a real app, this would generate a PDF or Excel report
      console.log('Generating report for course:', course);
      
      // Create a mock report structure
      const report = {
        courseInfo: {
          code: course.code,
          name: course.name,
          instructor: course.instructor,
          academicYear: config.academicYear,
          semester: config.semester
        },
        students: course.students.map(student => ({
          code: student.studentCode,
          name: student.getFullName(),
          scores: { ...student.scores },
          finalGrade: student.calculateFinalGrade()
        })),
        statistics: {
          average: course.calculateClassAverage(),
          totalStudents: course.students.length
        }
      };
      
      // In a real implementation, this would trigger file download
      console.log('Report data:', report);
      alert('Báo cáo đang được tạo. Vui lòng chờ trong giây lát...');
    },
    
    exportToExcel(course) {
      // Mock implementation for Excel export
      console.log('Exporting to Excel:', course);
      alert('Đang xuất file Excel. Vui lòng chờ trong giây lát...');
    }
  };
  
  // Student Request Handler
  const StudentRequestHandler = {
    requests: [],
    
    submitRequest(studentId, currentGrade, requestedGrade, reason) {
      const request = {
        id: this.requests.length + 1,
        studentId,
        currentGrade,
        requestedGrade,
        reason,
        status: 'Đang chờ',
        submissionDate: new Date()
      };
      
      this.requests.push(request);
      return request.id;
    },
    
    getRequests() {
      return this.requests;
    },
    
    approveRequest(requestId, comments) {
      const request = this.requests.find(r => r.id === requestId);
      if (request) {
        request.status = 'Đã chấp nhận';
        request.instructorComments = comments;
        
        // In a real app, this would also update the student's grade
      }
    },
    
    rejectRequest(requestId, comments) {
      const request = this.requests.find(r => r.id === requestId);
      if (request) {
        request.status = 'Từ chối';
        request.instructorComments = comments;
      }
    }
  };
  
  // Notification System
  const NotificationSystem = {
    notifications: [],
    
    sendNotification(userId, message, type = 'info') {
      const notification = {
        id: this.notifications.length + 1,
        userId,
        message,
        type,
        timestamp: new Date(),
        read: false
      };
      
      this.notifications.push(notification);
      
      // In a real app, this might trigger an email or push notification
      console.log(`Notification sent to user ${userId}: ${message}`);
    },
    
    markAsRead(notificationId) {
      const notification = this.notifications.find(n => n.id === notificationId);
      if (notification) {
        notification.read = true;
      }
    },
    
    getUnreadCount(userId) {
      return this.notifications.filter(n => n.userId === userId && !n.read).length;
    }
  };
  
  // Application initialization
  document.addEventListener('DOMContentLoaded', () => {
    // Initialize the app
    ScoreController.loadCourse(1);
    
    // Set up event listeners
    document.getElementById('saveScoresBtn').addEventListener('click', () => {
      ScoreController.saveAllScores();
    });
    
    document.getElementById('importExcelBtn').addEventListener('click', () => {
      // In a real app, this would open a file picker
      const mockFile = { name: 'grades.xlsx' };
      ScoreController.importFromExcel(mockFile);
    });
    
    // For demonstration purposes
    console.log('Score Management System initialized');
  });