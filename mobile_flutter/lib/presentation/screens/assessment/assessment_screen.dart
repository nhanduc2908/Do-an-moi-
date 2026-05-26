import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../data/models/assessment_model.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/assessment/assessment_card.dart';

class AssessmentScreen extends ConsumerStatefulWidget {
  const AssessmentScreen({super.key});

  @override
  ConsumerState<AssessmentScreen> createState() => _AssessmentScreenState();
}

class _AssessmentScreenState extends ConsumerState<AssessmentScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(assessmentProvider.notifier).loadAssessments();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessments'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () {},
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: state.assessments.length,
              itemBuilder: (context, index) {
                final assessment = state.assessments[index];
                return AssessmentCard(assessment: assessment);
              },
            ),
    );
  }
}