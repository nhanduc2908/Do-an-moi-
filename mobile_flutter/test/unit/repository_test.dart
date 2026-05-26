// Đường dẫn: mobile_flutter/test/unit/repository_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/data/repositories/auth_repository.dart';
import 'package:security_evaluation_app/data/repositories/assessment_repository.dart';
import 'package:security_evaluation_app/data/repositories/incident_repository.dart';
import '../helpers/test_data.dart';
import '../helpers/mock_factories.dart';

void main() {
  late AuthRepository authRepository;
  late AssessmentRepository assessmentRepository;
  late IncidentRepository incidentRepository;

  setUp(() {
    authRepository = MockFactories.createMockAuthRepository();
    assessmentRepository = AssessmentRepository();
    incidentRepository = IncidentRepository();
  });

  group('AuthRepository Tests', () {
    test('login returns user on success', () async {
      final response = await authRepository.login('test@example.com', 'password');
      
      expect(response.success, true);
      expect(response.data, isNotNull);
    });

    test('getCurrentUser returns cached user', () async {
      final user = await authRepository.getCurrentUser();
      
      expect(user, isNotNull);
    });
  });

  group('AssessmentRepository Tests', () {
    test('getAssessments returns list of assessments', () async {
      final response = await assessmentRepository.getAssessments();
      
      expect(response.success, true);
      expect(response.data, isNotNull);
    });
  });

  group('IncidentRepository Tests', () {
    test('getIncidents returns list of incidents', () async {
      final response = await incidentRepository.getIncidents();
      
      expect(response.success, true);
      expect(response.data, isNotNull);
    });
  });
}