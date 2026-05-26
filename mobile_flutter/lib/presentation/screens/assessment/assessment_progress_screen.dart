import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';

class AssessmentProgressScreen extends ConsumerStatefulWidget {
  const AssessmentProgressScreen({super.key, required this.assessmentId});
  final String assessmentId;

  @override
  ConsumerState<AssessmentProgressScreen> createState() => _AssessmentProgressScreenState();
}

class _AssessmentProgressScreenState extends ConsumerState<AssessmentProgressScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(assessmentProvider.notifier).loadAssessmentDetail(widget.assessmentId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(assessmentProvider);
    final assessment = state.currentAssessment;
    final progress = assessment?.progress ?? 0;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessment Progress'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : assessment == null
              ? const Center(child: Text('Assessment not found'))
              : Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(20),
                          child: Column(
                            children: [
                              const Text('Overall Progress', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                              const SizedBox(height: 16),
                              Stack(
                                alignment: Alignment.center,
                                children: [
                                  SizedBox(
                                    height: 150,
                                    width: 150,
                                    child: CircularProgressIndicator(
                                      value: progress / 100,
                                      strokeWidth: 12,
                                      backgroundColor: Colors.grey.shade200,
                                    ),
                                  ),
                                  Column(
                                    children: [
                                      Text(
                                        '$progress%',
                                        style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
                                      ),
                                      const Text('Complete'),
                                    ],
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      const Text('Section Progress', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 8),
                      _buildSectionProgress('Access Control', 80),
                      _buildSectionProgress('Cryptography', 60),
                      _buildSectionProgress('Network Security', 75),
                      _buildSectionProgress('Incident Response', 45),
                    ],
                  ),
                ),
    );
  }

  Widget _buildSectionProgress(String section, int progress) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(section, style: const TextStyle(fontWeight: FontWeight.bold)),
                Text('$progress%'),
              ],
            ),
            const SizedBox(height: 8),
            LinearProgressIndicator(
              value: progress / 100,
              backgroundColor: Colors.grey.shade200,
            ),
          ],
        ),
      ),
    );
  }
}