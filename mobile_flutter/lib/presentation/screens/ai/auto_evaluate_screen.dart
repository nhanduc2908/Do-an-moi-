import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/common/custom_button.dart';

class AutoEvaluateScreen extends ConsumerStatefulWidget {
  const AutoEvaluateScreen({super.key});

  @override
  ConsumerState<AutoEvaluateScreen> createState() => _AutoEvaluateScreenState();
}

class _AutoEvaluateScreenState extends ConsumerState<AutoEvaluateScreen> {
  bool _isEvaluating = false;
  double _progress = 0.0;
  String _status = 'Ready to start evaluation';

  Future<void> _startEvaluation() async {
    setState(() {
      _isEvaluating = true;
      _progress = 0.0;
      _status = 'Analyzing security controls...';
    });

    for (int i = 0; i <= 100; i += 10) {
      await Future.delayed(const Duration(milliseconds: 300));
      setState(() {
        _progress = i / 100;
        if (i == 30) _status = 'Evaluating access controls...';
        if (i == 60) _status = 'Checking compliance standards...';
        if (i == 80) _status = 'Generating recommendations...';
      });
    }

    setState(() {
      _isEvaluating = false;
      _status = 'Evaluation completed!';
    });

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Auto-evaluation completed successfully')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Auto Evaluation'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  children: [
                    const Icon(Icons.auto_awesome, size: 64, color: Colors.blue),
                    const SizedBox(height: 16),
                    const Text('AI-Powered Security Evaluation', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Text(_status, textAlign: TextAlign.center, style: const TextStyle(color: Colors.grey)),
                    const SizedBox(height: 24),
                    if (_isEvaluating) ...[
                      LinearProgressIndicator(value: _progress),
                      const SizedBox(height: 8),
                      Text('${(_progress * 100).toInt()}%'),
                    ],
                    const SizedBox(height: 24),
                    CustomButton(
                      text: _isEvaluating ? 'Evaluating...' : 'Start Evaluation',
                      onPressed: _isEvaluating ? null : _startEvaluation,
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
                  children: const [
                    Text('What will be evaluated:', style: TextStyle(fontWeight: FontWeight.bold)),
                    SizedBox(height: 8),
                    Text('• Access Control Policies'),
                    Text('• Data Protection Measures'),
                    Text('• Compliance Standards'),
                    Text('• Security Controls'),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}