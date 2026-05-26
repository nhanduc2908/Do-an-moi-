import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/assessment/assessment_history_card.dart';

class AssessmentHistory extends ConsumerStatefulWidget {
  const AssessmentHistory({super.key});

  @override
  ConsumerState<AssessmentHistory> createState() => _AssessmentHistoryState();
}

class _AssessmentHistoryState extends ConsumerState<AssessmentHistory> {
  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessment History'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: state.assessments.length,
              itemBuilder: (context, index) {
                final assessment = state.assessments[index];
                return AssessmentHistoryCard(assessment: assessment);
              },
            ),
    );
  }
}