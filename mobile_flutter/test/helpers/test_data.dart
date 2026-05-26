// Đường dẫn: mobile_flutter/test/helpers/test_data.dart

import 'package:security_evaluation_app/data/models/user_model.dart';
import 'package:security_evaluation_app/data/models/role_model.dart';
import 'package:security_evaluation_app/data/models/assessment_model.dart';
import 'package:security_evaluation_app/data/models/incident_model.dart';
import 'package:security_evaluation_app/data/models/vulnerability_model.dart';

class TestData {
  static const String testEmail = 'test@example.com';
  static const String testPassword = 'Test@123456';
  static const String testToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';

  static UserModel get testUser => UserModel(
    id: '1',
    name: 'Test User',
    email: testEmail,
    role: 'viewer',
    status: 'active',
    createdAt: DateTime(2024, 1, 1),
  );

  static UserModel get testAdmin => UserModel(
    id: '2',
    name: 'Admin User',
    email: 'admin@example.com',
    role: 'admin',
    status: 'active',
    createdAt: DateTime(2024, 1, 1),
  );

  static UserModel get testSecurityManager => UserModel(
    id: '3',
    name: 'Security Manager',
    email: 'manager@example.com',
    role: 'security_manager',
    status: 'active',
    createdAt: DateTime(2024, 1, 1),
  );

  static RoleModel get testRole => RoleModel(
    id: 1,
    name: 'viewer',
    displayName: 'Viewer',
    level: 10,
  );

  static AssessmentModel get testAssessment => AssessmentModel(
    id: '1',
    title: 'Security Assessment Q1 2024',
    type: 'security',
    score: 78.5,
    status: 'completed',
    progress: 100,
    createdAt: DateTime(2024, 1, 15),
  );

  static IncidentModel get testIncident => IncidentModel(
    id: '1',
    code: 'INC-001',
    title: 'Security Breach',
    severity: 'high',
    status: 'open',
    category: 'unauthorized_access',
    createdAt: DateTime(2024, 1, 10),
  );

  static VulnerabilityModel get testVulnerability => VulnerabilityModel(
    id: '1',
    cveId: 'CVE-2024-12345',
    title: 'SQL Injection Vulnerability',
    severity: 'high',
    cvssScore: 7.5,
    status: 'open',
    createdAt: DateTime(2024, 1, 5),
  );
}