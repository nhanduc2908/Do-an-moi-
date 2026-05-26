// Đường dẫn: mobile_flutter/test/helpers/test_helpers.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/data/models/user_model.dart';
import 'package:security_evaluation_app/data/models/assessment_model.dart';
import 'package:security_evaluation_app/data/models/incident_model.dart';

// Create a test widget
Widget createTestWidget(Widget child) {
  return MaterialApp(
    home: child,
  );
}

// Pump widget with provider
Future<void> pumpWidgetWithProviders(
  WidgetTester tester,
  Widget child, {
  List<Provider>? providers,
}) async {
  await tester.pumpWidget(
    MultiProvider(
      providers: providers ?? [],
      child: MaterialApp(home: child),
    ),
  );
}

// Create mock user
UserModel createMockUser({
  String id = '1',
  String name = 'Test User',
  String email = 'test@example.com',
  String role = 'viewer',
}) {
  return UserModel(
    id: id,
    name: name,
    email: email,
    role: role,
    status: 'active',
    createdAt: DateTime.now(),
  );
}

// Create mock assessment
AssessmentModel createMockAssessment({
  String id = '1',
  String title = 'Test Assessment',
  double score = 75.0,
  String status = 'completed',
}) {
  return AssessmentModel(
    id: id,
    title: title,
    score: score,
    status: status,
    createdAt: DateTime.now(),
  );
}

// Create mock incident
IncidentModel createMockIncident({
  String id = '1',
  String title = 'Test Incident',
  String severity = 'medium',
  String status = 'open',
}) {
  return IncidentModel(
    id: id,
    title: title,
    severity: severity,
    status: status,
    createdAt: DateTime.now(),
  );
}

// Wait for async operations
Future<void> waitForAsync() async {
  await Future.delayed(const Duration(milliseconds: 500));
}

// Tap and wait
Future<void> tapAndWait(WidgetTester tester, Finder finder) async {
  await tester.tap(finder);
  await tester.pumpAndSettle();
}