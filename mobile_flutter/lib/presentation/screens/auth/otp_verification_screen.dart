import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class OtpVerificationScreen extends ConsumerStatefulWidget {
  const OtpVerificationScreen({super.key, this.phoneNumber});
  final String? phoneNumber;

  @override
  ConsumerState<OtpVerificationScreen> createState() => _OtpVerificationScreenState();
}

class _OtpVerificationScreenState extends ConsumerState<OtpVerificationScreen> {
  final List<TextEditingController> _otpControllers = List.generate(6, (_) => TextEditingController());
  final List<FocusNode> _focusNodes = List.generate(6, (_) => FocusNode());
  bool _isLoading = false;
  int _timerSeconds = 60;
  bool _canResend = false;

  @override
  void initState() {
    super.initState();
    _startTimer();
  }

  void _startTimer() {
    Future.delayed(const Duration(seconds: 1), () {
      if (_timerSeconds > 0) {
        setState(() {
          _timerSeconds--;
          _startTimer();
        });
      } else {
        setState(() => _canResend = true);
      }
    });
  }

  void _onOtpChanged(int index, String value) {
    if (value.length == 1 && index < 5) {
      _focusNodes[index + 1].requestFocus();
    }
    if (value.isEmpty && index > 0) {
      _focusNodes[index - 1].requestFocus();
    }
  }

  String getOtpCode() {
    return _otpControllers.map((c) => c.text).join();
  }

  Future<void> _verifyOtp() async {
    final otp = getOtpCode();
    if (otp.length != 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter the 6-digit code')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    // Verify OTP logic here
    await Future.delayed(const Duration(seconds: 1));
    
    setState(() => _isLoading = false);
    context.go(RouteNames.home);
  }

  Future<void> _resendOtp() async {
    setState(() {
      _timerSeconds = 60;
      _canResend = false;
    });
    _startTimer();
    
    // Resend OTP logic here
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('OTP resent successfully')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Verify Phone')),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          children: [
            const SizedBox(height: 40),
            const Icon(Icons.sms, size: 80, color: Colors.blue),
            const SizedBox(height: 20),
            Text(
              'Enter Verification Code',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 8),
            Text(
              'We have sent a 6-digit code to ${widget.phoneNumber ?? 'your phone'}',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey[600]),
            ),
            const SizedBox(height: 40),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: List.generate(6, (index) => SizedBox(
                width: 50,
                child: CustomTextField(
                  controller: _otpControllers[index],
                  focusNode: _focusNodes[index],
                  textAlign: TextAlign.center,
                  keyboardType: TextInputType.number,
                  maxLength: 1,
                  onChanged: (value) => _onOtpChanged(index, value),
                ),
              )),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Verify',
              onPressed: _verifyOtp,
              isLoading: _isLoading,
            ),
            const SizedBox(height: 16),
            if (_canResend)
              TextButton(
                onPressed: _resendOtp,
                child: const Text('Resend Code'),
              )
            else
              Text(
                'Resend code in $_timerSeconds seconds',
                style: TextStyle(color: Colors.grey[600]),
              ),
          ],
        ),
      ),
    );
  }
}