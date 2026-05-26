import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/common/custom_button.dart';

class AssessmentResultScreen extends ConsumerStatefulWidget {
  const AssessmentResultScreen({super.key, required this.assessmentId});
  final String assessmentId;

  @override
  ConsumerState<AssessmentResultScreen> createState() => _AssessmentResultScreenState();
}

class _AssessmentResultScreenState extends ConsumerState<AssessmentResultScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(assessmentProvider.notifier).loadAssessmentDetail(widget.assessmentId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    final assessment = state.currentAssessment;
    final score = assessment?.score ?? 0;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessment Result'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : assessment == null
              ? const Center(child: Text('Assessment not found'))
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(24),
                          child: Column(
                            children: [
                              const Icon(Icons.assessment, size: 64, color: Colors.blue),
                              const SizedBox(height: 16),
                              Text(
                                'Your Score',
                                style: Theme.of(context).textTheme.titleLarge,
                              ),
                              const SizedBox(height: 8),
                              Text(
                                score.toStringAsFixed(1),
                                style: const TextStyle(fontSize: 48, fontWeight: FontWeight.bold, color: Colors.blue),
                              ),
                              const SizedBox(height: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                decoration: BoxDecoration(
                                  color: _getRiskColor(score).withOpacity(0.2),
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text(
                                  _getRiskLevel(score),
                                  style: TextStyle(color: _getRiskColor(score), fontWeight: FontWeight.bold),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Summary', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              Text('Total questions: 45'),
                              Text('Correct answers: ${(score / 100 * 45).toInt()}'),
                              Text('Incorrect answers: ${45 - (score / 100 * 45).toInt()}'),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: CustomButton(
                              text: 'View Details',
                              onPressed: () {},
                              isOutlined: true,
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: CustomButton(
                              text: 'Download Report',
                              onPressed: () {},
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
    );
  }

  Color _getRiskColor(double score) {
    if (score >= 80) return Colors.green;
    if (score >= 60) return Colors.orange;
    return Colors.red;
  }

  String _getRiskLevel(double score) {
    if (score >= 80) return 'Low Risk';
    if (score >= 60) return 'Medium Risk';
    return 'High Risk';
  }
}