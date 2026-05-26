// Đường dẫn: mobile_flutter/test/unit/model_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/data/models/user_model.dart';
import 'package:security_evaluation_app/data/models/assessment_model.dart';
import 'package:security_evaluation_app/data/models/incident_model.dart';
import 'package:security_evaluation_app/data/models/vulnerability_model.dart';
import '../helpers/test_data.dart';

void main() {
  group('UserModel Tests', () {
    test('UserModel fromJson creates correct object', () {
      final json = {
        'id': '1',
        'name': 'Test User',
        'email': 'test@example.com',
        'role': 'viewer',
        'status': 'active',
      };
      
      final user = UserModel.fromJson(json);
      
      expect(user.id, '1');
      expect(user.name, 'Test User');
      expect(user.email, 'test@example.com');
      expect(user.role, 'viewer');
      expect(user.status, 'active');
    });

    test('UserModel toJson creates correct map', () {
      final user = TestData.testUser;
      final json = user.toJson();
      
      expect(json['id'], user.id);
      expect(json['name'], user.name);
      expect(json['email'], user.email);
    });

    test('UserModel isAdmin returns true for admin role', () {
      final admin = TestData.testAdmin;
      expect(admin.isAdmin, true);
    });

    test('UserModel isAdmin returns false for viewer role', () {
      final viewer = TestData.testUser;
      expect(viewer.isAdmin, false);
    });

    test('UserModel initials returns correct initials', () {
      final user = UserModel(name: 'John Doe');
      expect(user.initials, 'JD');
    });

    test('UserModel initials returns single letter for single name', () {
      final user = UserModel(name: 'John');
      expect(user.initials, 'J');
    });
  });

  group('AssessmentModel Tests', () {
    test('AssessmentModel fromJson creates correct object', () {
      final json = {
        'id': '1',
        'title': 'Test Assessment',
        'score': 85.5,
        'status': 'completed',
      };
      
      final assessment = AssessmentModel.fromJson(json);
      
      expect(assessment.id, '1');
      expect(assessment.title, 'Test Assessment');
      expect(assessment.score, 85.5);
      expect(assessment.status, 'completed');
    });

    test('AssessmentModel isCompleted returns true for completed status', () {
      final assessment = AssessmentModel(status: 'completed');
      expect(assessment.isCompleted, true);
    });

    test('AssessmentModel isInProgress returns true for in_progress status', () {
      final assessment = AssessmentModel(status: 'in_progress');
      expect(assessment.isInProgress, true);
    });
  });

  group('IncidentModel Tests', () {
    test('IncidentModel fromJson creates correct object', () {
      final json = {
        'id': '1',
        'code': 'INC-001',
        'title': 'Test Incident',
        'severity': 'high',
        'status': 'open',
      };
      
      final incident = IncidentModel.fromJson(json);
      
      expect(incident.id, '1');
      expect(incident.code, 'INC-001');
      expect(incident.title, 'Test Incident');
      expect(incident.severity, 'high');
      expect(incident.status, 'open');
    });

    test('IncidentModel severityColor returns correct color', () {
      final critical = IncidentModel(severity: 'critical');
      expect(critical.severityColor, Colors.red);
      
      final high = IncidentModel(severity: 'high');
      expect(high.severityColor, Colors.orange);
      
      final medium = IncidentModel(severity: 'medium');
      expect(medium.severityColor, Colors.yellow.shade700);
      
      final low = IncidentModel(severity: 'low');
      expect(low.severityColor, Colors.green);
    });
  });

  group('VulnerabilityModel Tests', () {
    test('VulnerabilityModel fromJson creates correct object', () {
      final json = {
        'id': '1',
        'cve_id': 'CVE-2024-12345',
        'title': 'Test Vulnerability',
        'severity': 'HIGH',
        'cvss_score': 7.5,
      };
      
      final vuln = VulnerabilityModel.fromJson(json);
      
      expect(vuln.id, '1');
      expect(vuln.cveId, 'CVE-2024-12345');
      expect(vuln.title, 'Test Vulnerability');
      expect(vuln.severity, 'HIGH');
      expect(vuln.cvssScore, 7.5);
    });

    test('VulnerabilityModel isCritical returns true for CRITICAL severity', () {
      final vuln = VulnerabilityModel(severity: 'CRITICAL');
      expect(vuln.isCritical, true);
    });
  });
}