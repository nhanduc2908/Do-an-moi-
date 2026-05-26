// Đường dẫn: mobile_flutter/test/integration/api_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:security_evaluation_app/main.dart';
import 'package:security_evaluation_app/data/datasources/remote/api_client.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('API Integration Tests', () {
    late ApiClient apiClient;

    setUp(() {
      apiClient = ApiClient();
    });

    testWidgets('API client can make GET request', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      final response = await apiClient.get('/test');
      
      expect(response, isNotNull);
    });

    testWidgets('API client handles errors gracefully', (WidgetTester tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      final response = await apiClient.get('/invalid-endpoint');
      
      expect(response['success'], isFalse);
    });
  });
}