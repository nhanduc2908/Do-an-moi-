// Đường dẫn: mobile_flutter/test/providers/assessment_provider_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:security_evaluation_app/presentation/providers/assessment_provider.dart';
import 'package:security_evaluation_app/data/models/assessment_model.dart';
import '../mocks/mock_repository.dart';
import '../helpers/test_data.dart';

void main() {
  group('AssessmentProvider Tests', () {
    late ProviderContainer container;
    late MockAssessmentRepository mockRepository;

    setUp(() {
      mockRepository = MockRepositories.createMockAssessmentRepository();
      container = ProviderContainer();
    });

    tearDown(() {
      container.dispose();
    });

    group('Initial State Tests', () {
      test('initial state has empty assessments', () {
        final state = container.read(assessmentProvider);
        
        expect(state.assessments, isEmpty);
        expect(state.currentAssessment, isNull);
        expect(state.isLoading, false);
        expect(state.error, isNull);
        expect(state.hasMore, true);
        expect(state.currentPage, 1);
      });
    });

    group('loadAssessments Tests', () {
      test('loadAssessments updates state with data', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        await notifier.loadAssessments();
        
        final state = container.read(assessmentProvider);
        expect(state.assessments, isNotEmpty);
        expect(state.isLoading, false);
        expect(state.error, isNull);
      });

      test('loadAssessments sets loading state correctly', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        // Start loading
        final loadFuture = notifier.loadAssessments();
        
        // Check loading state
        expect(container.read(assessmentProvider).isLoading, true);
        
        await loadFuture;
        
        // Check finished state
        expect(container.read(assessmentProvider).isLoading, false);
      });

      test('loadAssessments refreshes when refresh=true', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        // First load
        await notifier.loadAssessments();
        final firstAssessments = container.read(assessmentProvider).assessments;
        
        // Refresh
        await notifier.loadAssessments(refresh: true);
        final refreshedAssessments = container.read(assessmentProvider).assessments;
        
        expect(refreshedAssessments, isNotEmpty);
      });

      test('loadAssessments appends when not refreshing', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        // First load
        await notifier.loadAssessments();
        final initialCount = container.read(assessmentProvider).assessments.length;
        
        // Second load (append)
        await notifier.loadAssessments(refresh: false);
        final finalCount = container.read(assessmentProvider).assessments.length;
        
        expect(finalCount, greaterThanOrEqualTo(initialCount));
      });

      test('loadAssessments handles error', () async {
        // Create a repository that throws error
        final errorRepository = MockAssessmentRepository();
        // Configure to return error
        
        final errorContainer = ProviderContainer();
        
        await errorContainer.read(assessmentProvider.notifier).loadAssessments();
        
        final state = errorContainer.read(assessmentProvider);
        expect(state.error, isNotNull);
        expect(state.isLoading, false);
        
        errorContainer.dispose();
      });
    });

    group('loadAssessmentDetail Tests', () {
      test('loadAssessmentDetail updates current assessment', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        await notifier.loadAssessmentDetail('1');
        
        final state = container.read(assessmentProvider);
        expect(state.currentAssessment, isNotNull);
        expect(state.currentAssessment?.id, '1');
      });

      test('loadAssessmentDetail returns assessment data', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        final assessment = await notifier.loadAssessmentDetail('1');
        
        expect(assessment, isNotNull);
        expect(assessment?.id, '1');
      });

      test('loadAssessmentDetail sets loading state', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        final loadFuture = notifier.loadAssessmentDetail('1');
        
        expect(container.read(assessmentProvider).isLoading, true);
        
        await loadFuture;
        
        expect(container.read(assessmentProvider).isLoading, false);
      });

      test('loadAssessmentDetail returns null on error', () async {
        final errorContainer = ProviderContainer();
        
        final assessment = await errorContainer.read(assessmentProvider.notifier)
            .loadAssessmentDetail('invalid');
        
        expect(assessment, isNull);
        
        errorContainer.dispose();
      });
    });

    group('createAssessment Tests', () {
      test('createAssessment adds new assessment', () async {
        final notifier = container.read(assessmentProvider.notifier);
        final initialCount = container.read(assessmentProvider).assessments.length;
        
        final success = await notifier.createAssessment({
          'title': 'New Assessment',
          'type': 'security',
          'scope': ['network', 'application'],
        });
        
        expect(success, true);
        expect(container.read(assessmentProvider).assessments.length, 
               greaterThan(initialCount));
      });

      test('createAssessment adds to beginning of list', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        await notifier.createAssessment({
          'title': 'Latest Assessment',
          'type': 'security',
        });
        
        final firstAssessment = container.read(assessmentProvider).assessments.first;
        expect(firstAssessment.title, 'Latest Assessment');
      });

      test('createAssessment sets loading state', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        final createFuture = notifier.createAssessment({
          'title': 'New Assessment',
          'type': 'security',
        });
        
        expect(container.read(assessmentProvider).isLoading, true);
        
        await createFuture;
        
        expect(container.read(assessmentProvider).isLoading, false);
      });

      test('createAssessment returns false on error', () async {
        final errorContainer = ProviderContainer();
        
        final success = await errorContainer.read(assessmentProvider.notifier)
            .createAssessment({});
        
        expect(success, false);
        
        errorContainer.dispose();
      });
    });

    group('submitAssessment Tests', () {
      test('submitAssessment returns true on success', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        // First load an assessment
        await notifier.loadAssessmentDetail('1');
        
        const success = await notifier.submitAssessment('1', {
          'answers': {
            'criteria_1': 'Yes',
            'criteria_2': 'No',
          },
        });
        
        expect(success, true);
      });

      test('submitAssessment reloads assessment after submit', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        await notifier.loadAssessmentDetail('1');
        await notifier.submitAssessment('1', {});
        
        final state = container.read(assessmentProvider);
        expect(state.currentAssessment, isNotNull);
      });

      test('submitAssessment sets loading state', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        final submitFuture = notifier.submitAssessment('1', {});
        
        expect(container.read(assessmentProvider).isLoading, true);
        
        await submitFuture;
        
        expect(container.read(assessmentProvider).isLoading, false);
      });

      test('submitAssessment returns false on error', () async {
        final errorContainer = ProviderContainer();
        
        const success = await errorContainer.read(assessmentProvider.notifier)
            .submitAssessment('invalid', {});
        
        expect(success, false);
        
        errorContainer.dispose();
      });
    });

    group('clearError Tests', () {
      test('clearError removes error message', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        // Trigger an error
        await notifier.loadAssessmentDetail('invalid');
        expect(container.read(assessmentProvider).error, isNotNull);
        
        // Clear error
        notifier.clearError();
        
        expect(container.read(assessmentProvider).error, isNull);
      });
    });

    group('Pagination Tests', () {
      test('hasMore updates correctly', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        await notifier.loadAssessments();
        
        final state = container.read(assessmentProvider);
        expect(state.hasMore, isBool);
      });

      test('currentPage increments after load', () async {
        final notifier = container.read(assessmentProvider.notifier);
        
        expect(container.read(assessmentProvider).currentPage, 1);
        
        await notifier.loadAssessments(refresh: false);
        
        expect(container.read(assessmentProvider).currentPage, 2);
      });
    });
  });
}