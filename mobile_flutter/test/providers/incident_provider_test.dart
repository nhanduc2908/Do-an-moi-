// Đường dẫn: mobile_flutter/test/providers/incident_provider_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:security_evaluation_app/presentation/providers/incident_provider.dart';
import '../mocks/mock_repository.dart';
import '../helpers/test_data.dart';

void main() {
  late ProviderContainer container;
  late MockIncidentRepository mockRepository;

  setUp(() {
    mockRepository = MockRepositories.createMockIncidentRepository();
    container = ProviderContainer();
  });

  tearDown(() {
    container.dispose();
  });

  group('IncidentProvider Tests', () {
    test('initial state has empty incidents', () {
      final state = container.read(incidentProvider);
      
      expect(state.incidents, isEmpty);
      expect(state.isLoading, false);
    });

    test('loadIncidents updates state', () async {
      final notifier = container.read(incidentProvider.notifier);
      
      await notifier.loadIncidents();
      
      final state = container.read(incidentProvider);
      expect(state.incidents, isNotEmpty);
    });
  });
}