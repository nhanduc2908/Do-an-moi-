// Đường dẫn: mobile_flutter/test/mocks/mock_repository.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/data/repositories/auth_repository.dart';
import 'package:security_evaluation_app/data/repositories/assessment_repository.dart';
import 'package:security_evaluation_app/data/repositories/incident_repository.dart';
import '../helpers/test_data.dart';

@GenerateMocks([
  AuthRepository,
  AssessmentRepository,
  IncidentRepository,
])

class MockRepositories {
  static MockAuthRepository createMockAuthRepository() {
    final mock = MockAuthRepository();
    
    when(mock.login(any, any)).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: TestData.testUser));
    when(mock.getCurrentUser()).thenAnswer((_) async => TestData.testUser);
    when(mock.logout()).thenAnswer((_) async => ApiResponseModel(success: true));
    
    return mock;
  }

  static MockAssessmentRepository createMockAssessmentRepository() {
    final mock = MockAssessmentRepository();
    
    when(mock.getAssessments()).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: [TestData.testAssessment]));
    when(mock.getAssessmentDetail(any)).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: TestData.testAssessment));
    
    return mock;
  }

  static MockIncidentRepository createMockIncidentRepository() {
    final mock = MockIncidentRepository();
    
    when(mock.getIncidents()).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: [TestData.testIncident]));
    when(mock.getIncidentDetail(any)).thenAnswer((_) async => 
      ApiResponseModel(success: true, data: TestData.testIncident));
    
    return mock;
  }
}